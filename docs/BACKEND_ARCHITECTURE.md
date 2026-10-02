# BETA Industrial backend architecture

This blueprint complements the static site currently in the repository. It targets Laravel 13.x (PHP 8.3+) and MySQL 8.0.21+, InnoDB, `utf8mb4`, Redis, private S3-compatible object storage, and UTC timestamps. The complete initial DDL and Egypt/Saudi seed examples are in [`database/schema.mysql.sql`](../database/schema.mysql.sql). Laravel 13.x is the current major release and requires PHP 8.3; verify its official support table during implementation planning.

The PDF proposal is treated as product context: catalog categories and technical data, engineering/manufacturing/installation services, sectors, project galleries, quality and certification resources, insights, career applications, downloads, and the quote form with drawing/BOQ upload. The palette screenshot is a presentation input and does not change persistence design.

## 1. Design invariants

- Internal primary keys are unsigned `BIGINT` values for compact indexes. Every public entity has a unique ULID `public_id` (`CHAR(26)`, Crockford Base32) and route binding uses that field. Never serialize internal IDs into public responses. Use `Route::bind`/`getRouteKeyName()` or scoped explicit lookup; UUIDv4 can be used instead if the organization standardizes on it.
- Monetary values use `DECIMAL(15,2)` and exchange rates use `DECIMAL(18,6)`. Quantities may use `DECIMAL(15,3)`; no financial arithmetic uses PHP floats. Use integer minor units or BCMath/string decimal arithmetic in PHP, and round according to the currency's `minor_unit` only at defined accounting boundaries.
- Country, language, currency, payment method, country-payment availability, and tax jurisdiction/rate are rows. Expansion is inserts and configuration, without `ALTER TABLE`. Currency conversion is explicit; invoices snapshot their currency, amounts, and applied tax rules so later configuration changes cannot rewrite history.
- Workflow values (`status_code`, event types, payment method types) are validated string codes, not SQL `ENUM`s. Add/retire code values through configuration and application validation.
- Financial records and historical content relationships use `RESTRICT`; owned translation rows, pivots, and quotation children cascade only where safe. Invoice/payment records are never hard-deleted.
- `attachments` is a private object-storage registry with a polymorphic owner. Polymorphic relations cannot have a conventional FK to every possible owner table, so attachment services enforce owner existence and cleanup on owner deletion; object keys are unique, randomized and never derived from user filenames.

## 2. Schema map

| Area | Tables | Responsibility |
|---|---|---|
| Market configuration | `countries`, `currencies`, `languages`, `country_languages`, `country_currencies`, `exchange_rates` | Market defaults, supported locales/currencies and dated conversion quotes |
| Access and accountability | `users`, `roles`, `permissions`, pivots, `audit_logs`, `login_attempts` | Admin authorization, immutable audit history and failed login events |
| Uploads | `attachments` | Private object key, content metadata, scanner state, polymorphic owner, orphan cleanup |
| Lead and quote flow | `companies`, `form_definitions`, `form_fields`, `leads`, `rfqs`, `quotations`, `quotation_lines` | Versioned, data-driven forms; lead intake; RFQ lifecycle; commercial quote; BOQ attachment ownership |
| Catalog and portfolio | `product_categories`, `products`, product pivots, `product_attributes`, `product_attribute_values`, `services`, `sectors`, `projects`, project pivots, `partners`, `site_metrics` | Product search/specifications, category hierarchy, service capabilities, sector tags, project gallery, client/partner logos and homepage proof points |
| Editorial and hiring | `articles`, article categories/pivots, `job_postings`, `job_applications` | Draft/published content, article categories, hiring and candidate review pipeline |
| Regional finance | `payment_methods`, `country_payment_methods`, `tax_jurisdictions`, `tax_rates`, `invoices`, `invoice_lines`, `payments`, `payment_events` | Available channels, effective tax rules, immutable invoice snapshots and idempotent payment processing |
| Downloads and localization | `resources`, `translation_subjects`, `translations` | Company profile, certificates and other downloadable files; per-field locale rows with a real cascading FK to the translation owner |

The SQL includes Egypt/EGP and Saudi Arabia/SAR, Arabic and English, requested channel records, and starting standard VAT examples (Egypt 14%, Saudi Arabia 15%, effective 2026-01-01). Confirm the actual legal tax rates, scope, exemption rules, rounding policy, invoice numbering, retention period, and e-invoicing requirements with local tax/accounting counsel before production. Rates are dated data, not constants in code.

## 3. Laravel model and service blueprint

### Models and relationships

- `Country belongsTo Currency(default), belongsTo Language(default), belongsToMany Language/Currency`; `Currency hasMany ExchangeRate` in base and quote directions.
- `User belongsToMany Role`; `Role belongsToMany Permission`.
- `Company hasMany Lead/Invoice`; `Lead belongsTo Company/Country/assignee, hasMany Rfq`; `Rfq belongsTo Lead/Currency, hasMany Quotation`.
- `Quotation hasMany QuotationLine, belongsTo Rfq/Currency`; `Invoice hasMany InvoiceLine/Payment, belongsTo Company/Country/Currency/Quotation`.
- `Payment belongsTo Invoice/PaymentMethod/Currency, hasMany PaymentEvent`; `PaymentMethod belongsToMany Country through CountryPaymentMethod`.
- `Service morphMany Attachment`; `Partner morphMany Attachment` for logos; `SiteMetric` stores editable display values. `Product belongsToMany ProductCategory, hasMany ProductAttributeValue, morphMany Attachment`; `ProductCategory belongsTo parent and hasMany children`; `Project belongsToMany Sector/Product, morphMany Attachment`.
- `Resource morphMany Attachment`; publish only approved, public resources. Company profile, ISO certificates, datasheets and brochures live as private attachments and download through the same policy-checked signed URL flow.
- `Article belongsTo User, belongsToMany ArticleCategory, morphMany Attachment`; `JobPosting hasMany JobApplication`; `JobApplication morphMany Attachment`.
- `Attachment morphTo attachable`; `Translatable` resolves a `TranslationSubject` registry row for the entity and uses its `hasMany(Translation::class)` relation; `AuditLog belongsTo User` (nullable actor).

Recommended shared traits:

- `HasPublicId`: assign a ULID at creation; route key is `public_id`; hide `id` from API resources.
- `Auditable`: observer hooks capture created/updated/deleted/restored transitions. Capture only changed, allow-listed attributes and redact passwords, tokens, receipts' sensitive metadata, and secrets.
- `Translatable`: locale-aware translation lookup with configured fallback; cache translation bundles by entity type, public ID, and locale.
- `HasAttachments`: named attachment collections with server-side MIME/size policy.
- `SoftDeletes`: for users and editable/catalogue/content entities where restore is meaningful. Do not soft-delete invoices/payments as an operational substitute for reversal.

Protect mass assignment with explicit `$fillable` per model or `$guarded = ['id', 'public_id']` plus request FormRequests and DTOs. Never pass request input directly to `create()`/`update()`. Use policy checks and tenant/company scoping on every admin and customer operation. Define API resources to expose only intended fields and `public_id`.

For translation ownership, create one `translation_subjects` row per translatable model (unique `(subject_type, subject_id)`) in the same transaction as the model. On model deletion, delete that owner row in the same transaction; the `translations.translation_subject_id` foreign key cascades all locale rows. This retains a single translation table without relying on an unenforced polymorphic parent FK.

### Transaction boundaries and concurrency

- Wrap invoice creation, invoice line insertion, totals snapshot, and audit event in one DB transaction. Lock/update invoice balances with `SELECT ... FOR UPDATE` or atomic conditional updates.
- Quote conversion to invoice is idempotent: unique invoice number/public ID plus an application idempotency token; enforce one active invoice per business event in service logic or a dedicated unique source key if required.
- Verify payment webhooks only after signature verification and provider-event idempotency check. Persist the raw/minimized provider event before asynchronous processing. Do not trust redirect query parameters as payment confirmation.
- Invoice `payment_status` is derived within a transaction from successfully verified payment allocations, not directly from an upload. This minimal design assumes one invoice currency and payments in that same currency; cross-currency settlement requires a persisted settlement conversion snapshot before enabling it.
- Since MySQL does not enforce non-overlapping effective date intervals, tax configuration service must reject overlapping validity periods for a jurisdiction/code and invoice issuance must select exactly one applicable rate.

## 4. Secure file, receipt and BOQ workflows

1. Authorize the upload action and apply per-purpose extension allowlists, server-detected MIME checks, size limits, rate limits, and CSRF/auth controls. Use a temporary private upload or a short-lived signed S3 upload constrained to a generated key and declared maximum size. Ignore the submitted path and filename when choosing `object_key`.
2. Create `attachments` row in `queued` scan state; record original filename only as escaped display metadata. Hash content with SHA-256; malware scan and optional PDF/image parsing happen in a queue. Reject mismatches, archives, executable content, and active content unless a specific workflow requires it.
3. Accept BOQ/drawing PDFs, spreadsheets, and CAD formats under a strict, configurable allowlist; parse/OCR/thumbnail work runs asynchronously in isolated workers with CPU/time/memory caps. A failed parse leaves the original private file available only to authorized staff.
4. For InstaPay/bank-wire proof, associate attachment with a payment draft and set payment `status_code='pending'`. Admin verifies amount, currency, invoice, receipt/reference, and duplicate payment risk. In one transaction, lock invoice/payment, record verifier and timestamp, mark payment verified, recalculate paid amount/status and append audit log. A rejection stores a reason and leaves invoice unpaid. Receipt presence alone never pays an invoice.
5. Serve private downloads through an authorization endpoint that checks the attachment owner and user policy, then returns a short-lived signed URL (e.g. 60-300 seconds). Set safe content disposition, `X-Content-Type-Options: nosniff`, and avoid public bucket ACLs. Never store files beneath `public/`.
6. Orphan collector runs daily: find unlinked attachment rows older than a safety window (for example 24 hours) in bounded chunks, lock rows, confirm still unlinked, delete object and metadata, and report failures for retry. Purge only according to documented retention rules; keep payment evidence and career records for the approved legal period.

## 5. Audit, database operations and performance

- Append an `audit_logs` entry for every insert, update, soft-delete, restore, admin action, and failed login. Include actor (nullable for unauthenticated events), event code, model type and stable public identifier as `model_id`, old/new JSON values, request correlation ID, IP (packed IPv4/IPv6), and user agent. Record failed login events in both `login_attempts` and the centralized audit stream. Never log passwords, bearer tokens, full card data, or full webhook secrets.
- Audit writes should be in the same transaction as the business change when possible. Restrict update/delete privileges on the audit table for application runtime credentials; only the archive service can expire rows. Database-native DDL changes are outside this application trail and should use reviewed migration logs.
- Begin with monthly archive/export of older audit rows to immutable encrypted object storage, checksum and verify export, retain according to policy, then delete in bounded batches from MySQL. Keep hot-window indexes (`occurred_at`, actor, model, event) and monitor lag/storage. For very high volume, build an archive table and rotate with a tested procedure; MySQL partitioning requires care because partition keys must be part of unique keys and partitioned InnoDB tables do not support FKs. Avoid adding partitions without proving those constraints against this schema.
- Redis keys: `master:countries:v1`, `master:currencies:v1`, `master:payment-methods:{countryIso2}:{currencyCode}:v1`, `catalog:products:{locale}:{filterHash}:v1`. Cache serialized public DTOs, not live Eloquent instances or private configuration. Invalidate/version keys after admin writes; use short TTL plus explicit invalidation. Do not cache tax decisions past the effective-rule boundary or cache balances/authorization decisions.
- Use composite indexes for dashboard filters and pagination. Prefer cursor pagination by `(created_at,id)` or `public_id` to large offsets. Use read replicas only for non-financial dashboards with an explicit staleness budget; checkout, payment verification and invoice reads use the primary.
- Queue PDF generation, image derivatives, BOQ inspection, malware scanning, email and payment webhook processing. Use Redis-backed queues, retries with backoff, bounded worker concurrency, dead-letter visibility, idempotent jobs, and an outbox pattern where a DB commit must reliably dispatch a job.
- Use TLS, least-privilege DB accounts, encrypted backups, tested point-in-time recovery, secrets manager references for provider keys, strict CORS, request validation, CSRF for browser sessions, rate limits, and monitored admin MFA. Payment gateway secrets never belong in the `payment_methods.configuration` JSON; that column is for non-secret options or secret-manager reference names only.

## 6. Implementation decisions before go-live

1. Confirm legal invoice/tax numbering and retention obligations in Egypt and KSA; configure dated rules and preserve evidence for the required period.
2. Decide the allowed document formats and maximum sizes for BOQs, CVs, receipts, datasheets, certifications and company profile downloads.
3. Define lead/quotation/payment/application status transition matrices, role permissions, approval separation, and dashboard metric definitions.
4. Decide if customer accounts, multi-company tenancy, quote revisioning, invoice installments, partial credit notes, or settlement in a currency different from the invoice currency are in initial scope. Add dedicated relational entities for these before launch rather than placing core financial state only in JSON.
5. Run the DDL against the exact MySQL release in CI/staging and exercise rollback/recovery and representative query plans before production deployment.

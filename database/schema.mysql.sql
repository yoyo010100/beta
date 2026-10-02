-- BETA Industrial initial schema
-- Target: MySQL 8.0.21+ / InnoDB / utf8mb4. Store timestamps in UTC.
-- Internal BIGINT keys are never exposed. Public routes use ULID CHAR(26).
-- Expansion of countries, currencies, languages, payment methods and tax rules is row-only.

SET NAMES utf8mb4 COLLATE utf8mb4_0900_ai_ci;
SET time_zone = '+00:00';

CREATE TABLE currencies (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code CHAR(3) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(80) NOT NULL,
    symbol VARCHAR(12) NOT NULL,
    minor_unit TINYINT UNSIGNED NOT NULL DEFAULT 2,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_currencies_code (code), KEY ix_currencies_active (is_active, code),
    CONSTRAINT chk_currency_minor_unit CHECK (minor_unit <= 6)
) ENGINE=InnoDB;

CREATE TABLE languages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(16) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(80) NOT NULL,
    native_name VARCHAR(80) NOT NULL,
    text_direction VARCHAR(3) NOT NULL DEFAULT 'ltr',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_languages_code (code), KEY ix_languages_active (is_active, code),
    CONSTRAINT chk_language_direction CHECK (text_direction IN ('ltr','rtl'))
) ENGINE=InnoDB;

CREATE TABLE countries (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    iso2 CHAR(2) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    iso3 CHAR(3) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(120) NOT NULL,
    dialing_code VARCHAR(8) NULL,
    default_currency_id BIGINT UNSIGNED NULL,
    default_language_id BIGINT UNSIGNED NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_countries_iso2 (iso2), UNIQUE KEY uq_countries_iso3 (iso3),
    KEY ix_countries_active (is_active, name),
    CONSTRAINT fk_countries_currency FOREIGN KEY (default_currency_id) REFERENCES currencies(id) ON DELETE RESTRICT,
    CONSTRAINT fk_countries_language FOREIGN KEY (default_language_id) REFERENCES languages(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE country_languages (
    country_id BIGINT UNSIGNED NOT NULL,
    language_id BIGINT UNSIGNED NOT NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (country_id, language_id), KEY ix_country_languages_language (language_id, country_id),
    CONSTRAINT fk_country_languages_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    CONSTRAINT fk_country_languages_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE country_currencies (
    country_id BIGINT UNSIGNED NOT NULL,
    currency_id BIGINT UNSIGNED NOT NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (country_id, currency_id), KEY ix_country_currencies_currency (currency_id, country_id),
    CONSTRAINT fk_country_currencies_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    CONSTRAINT fk_country_currencies_currency FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE exchange_rates (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    base_currency_id BIGINT UNSIGNED NOT NULL,
    quote_currency_id BIGINT UNSIGNED NOT NULL,
    rate DECIMAL(18,6) NOT NULL,
    source VARCHAR(80) NOT NULL,
    effective_at DATETIME NOT NULL,
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id), UNIQUE KEY uq_exchange_rate_effective (base_currency_id, quote_currency_id, effective_at),
    KEY ix_exchange_rate_lookup (base_currency_id, quote_currency_id, effective_at),
    CONSTRAINT chk_exchange_rate_positive CHECK (rate > 0),
    CONSTRAINT fk_exchange_base_currency FOREIGN KEY (base_currency_id) REFERENCES currencies(id) ON DELETE RESTRICT,
    CONSTRAINT fk_exchange_quote_currency FOREIGN KEY (quote_currency_id) REFERENCES currencies(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(160) NOT NULL,
    email VARCHAR(254) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_users_public_id (public_id), UNIQUE KEY uq_users_email (email),
    KEY ix_users_active (is_active, deleted_at)
) ENGINE=InnoDB;

CREATE TABLE roles (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_roles_code (code)
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    description VARCHAR(255) NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_permissions_code (code)
) ENGINE=InnoDB;

CREATE TABLE role_user (
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id), KEY ix_role_user_role (role_id, user_id),
    CONSTRAINT fk_role_user_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_role_user_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE permission_role (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id), KEY ix_permission_role_permission (permission_id, role_id),
    CONSTRAINT fk_permission_role_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_permission_role_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE attachments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    disk VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    object_key VARCHAR(512) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    detected_mime VARCHAR(127) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    extension VARCHAR(12) CHARACTER SET ascii COLLATE ascii_bin NULL,
    size_bytes BIGINT UNSIGNED NOT NULL,
    sha256 BINARY(32) NOT NULL,
    scan_status VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'queued',
    uploaded_by_user_id BIGINT UNSIGNED NULL,
    attachable_type VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin NULL,
    attachable_id BIGINT UNSIGNED NULL,
    linked_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_attachments_public_id (public_id), UNIQUE KEY uq_attachments_object_key (disk, object_key),
    KEY ix_attachments_attachable (attachable_type, attachable_id), KEY ix_attachments_orphans (linked_at, created_at),
    KEY ix_attachments_scan_status (scan_status, created_at), KEY ix_attachments_uploader (uploaded_by_user_id, created_at),
    CONSTRAINT fk_attachments_uploader FOREIGN KEY (uploaded_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT chk_attachment_size CHECK (size_bytes > 0)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    occurred_at DATETIME(6) NOT NULL,
    actor_user_id BIGINT UNSIGNED NULL,
    event_type VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    model_type VARCHAR(160) CHARACTER SET ascii COLLATE ascii_bin NULL,
    model_id VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARBINARY(16) NULL,
    user_agent VARCHAR(768) NULL,
    request_id CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NULL,
    metadata JSON NULL,
    PRIMARY KEY (id), KEY ix_audit_occurred (occurred_at, id), KEY ix_audit_actor (actor_user_id, occurred_at),
    KEY ix_audit_model (model_type, model_id, occurred_at), KEY ix_audit_event (event_type, occurred_at),
    CONSTRAINT fk_audit_actor FOREIGN KEY (actor_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE login_attempts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,
    attempted_email_hash BINARY(32) NOT NULL,
    succeeded TINYINT(1) NOT NULL DEFAULT 0,
    ip_address VARBINARY(16) NULL,
    user_agent VARCHAR(768) NULL,
    occurred_at DATETIME(6) NOT NULL,
    PRIMARY KEY (id), KEY ix_login_email_time (attempted_email_hash, occurred_at), KEY ix_login_ip_time (ip_address, occurred_at),
    CONSTRAINT fk_login_attempt_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE companies (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(200) NOT NULL,
    registration_number VARCHAR(100) NULL,
    tax_registration_number VARCHAR(100) NULL,
    country_id BIGINT UNSIGNED NOT NULL,
    email VARCHAR(254) NULL,
    phone VARCHAR(40) NULL,
    address TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_companies_public_id (public_id), KEY ix_companies_country (country_id, name),
    CONSTRAINT fk_companies_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE form_definitions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    code VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    version SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_form_definitions_public_id (public_id),
    UNIQUE KEY uq_form_definitions_code_version (code, version), KEY ix_form_definitions_active (code, is_active)
) ENGINE=InnoDB;

CREATE TABLE form_fields (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    form_definition_id BIGINT UNSIGNED NOT NULL,
    field_key VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    field_type VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    validation_rules JSON NULL,
    field_options JSON NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_form_field_key (form_definition_id, field_key),
    KEY ix_form_fields_order (form_definition_id, sort_order),
    CONSTRAINT fk_form_fields_definition FOREIGN KEY (form_definition_id) REFERENCES form_definitions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE leads (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    company_id BIGINT UNSIGNED NULL,
    form_definition_id BIGINT UNSIGNED NULL,
    country_id BIGINT UNSIGNED NULL,
    contact_name VARCHAR(160) NOT NULL,
    email VARCHAR(254) NOT NULL,
    phone VARCHAR(40) NULL,
    project_type VARCHAR(120) NULL,
    project_location VARCHAR(255) NULL,
    source VARCHAR(80) NULL,
    assigned_to_user_id BIGINT UNSIGNED NULL,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'new',
    details JSON NULL,
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_leads_public_id (public_id), KEY ix_leads_status_created (status_code, created_at),
    KEY ix_leads_assignee_status (assigned_to_user_id, status_code, created_at), KEY ix_leads_company (company_id),
    CONSTRAINT fk_leads_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE RESTRICT,
    CONSTRAINT fk_leads_form_definition FOREIGN KEY (form_definition_id) REFERENCES form_definitions(id) ON DELETE RESTRICT,
    CONSTRAINT fk_leads_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE RESTRICT,
    CONSTRAINT fk_leads_assignee FOREIGN KEY (assigned_to_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE rfqs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    lead_id BIGINT UNSIGNED NOT NULL,
    rfq_number VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    requested_currency_id BIGINT UNSIGNED NULL,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'received',
    due_at DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_rfqs_public_id (public_id), UNIQUE KEY uq_rfqs_number (rfq_number),
    KEY ix_rfqs_lead_status (lead_id, status_code, created_at),
    CONSTRAINT fk_rfqs_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE RESTRICT,
    CONSTRAINT fk_rfqs_currency FOREIGN KEY (requested_currency_id) REFERENCES currencies(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE quotations (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    rfq_id BIGINT UNSIGNED NOT NULL,
    quotation_number VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    currency_id BIGINT UNSIGNED NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    tax_total DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'draft',
    valid_until DATE NULL,
    created_by_user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_quotations_public_id (public_id), UNIQUE KEY uq_quotations_number (quotation_number),
    KEY ix_quotations_rfq_status (rfq_id, status_code),
    CONSTRAINT chk_quotation_amounts CHECK (subtotal >= 0 AND tax_total >= 0 AND total >= 0),
    CONSTRAINT fk_quotations_rfq FOREIGN KEY (rfq_id) REFERENCES rfqs(id) ON DELETE RESTRICT,
    CONSTRAINT fk_quotations_currency FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE RESTRICT,
    CONSTRAINT fk_quotations_creator FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE quotation_lines (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quotation_id BIGINT UNSIGNED NOT NULL,
    line_number SMALLINT UNSIGNED NOT NULL,
    description VARCHAR(500) NOT NULL,
    quantity DECIMAL(15,3) NOT NULL,
    unit VARCHAR(32) NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    line_total DECIMAL(15,2) NOT NULL,
    specification JSON NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_quotation_line (quotation_id, line_number),
    CONSTRAINT chk_quotation_line_amounts CHECK (quantity >= 0 AND unit_price >= 0 AND line_total >= 0),
    CONSTRAINT fk_quotation_lines_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE sectors (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    slug VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_sectors_public_id (public_id), UNIQUE KEY uq_sectors_slug (slug), KEY ix_sectors_order (is_active, sort_order)
) ENGINE=InnoDB;

CREATE TABLE services (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    slug VARCHAR(140) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_services_public_id (public_id), UNIQUE KEY uq_services_slug (slug),
    KEY ix_services_order (is_active, sort_order)
) ENGINE=InnoDB;

CREATE TABLE partners (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(180) NOT NULL,
    website_url VARCHAR(512) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_partners_public_id (public_id), KEY ix_partners_order (is_active, sort_order)
) ENGINE=InnoDB;

CREATE TABLE resources (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    slug VARCHAR(160) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    resource_type VARCHAR(60) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    is_public TINYINT(1) NOT NULL DEFAULT 1,
    published_at DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_resources_public_id (public_id), UNIQUE KEY uq_resources_slug (slug),
    KEY ix_resources_type_public (resource_type, is_public, published_at)
) ENGINE=InnoDB;

CREATE TABLE site_metrics (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    display_value VARCHAR(80) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_site_metrics_code (code), KEY ix_site_metrics_order (is_active, sort_order)
) ENGINE=InnoDB;

CREATE TABLE product_categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    parent_id BIGINT UNSIGNED NULL,
    slug VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_product_categories_public_id (public_id), UNIQUE KEY uq_product_categories_slug (slug),
    KEY ix_product_categories_parent (parent_id, is_active, sort_order),
    CONSTRAINT fk_product_categories_parent FOREIGN KEY (parent_id) REFERENCES product_categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE products (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    sku VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NULL,
    slug VARCHAR(160) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_products_public_id (public_id), UNIQUE KEY uq_products_slug (slug),
    UNIQUE KEY uq_products_sku (sku), KEY ix_products_active_order (is_active, sort_order)
) ENGINE=InnoDB;

CREATE TABLE product_category_product (
    product_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (product_id, category_id), KEY ix_product_category_category (category_id, product_id),
    CONSTRAINT fk_pcp_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_pcp_category FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE product_attributes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    value_type VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'text',
    unit VARCHAR(32) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_product_attributes_code (code)
) ENGINE=InnoDB;

CREATE TABLE product_attribute_values (
    product_id BIGINT UNSIGNED NOT NULL,
    attribute_id BIGINT UNSIGNED NOT NULL,
    value_text TEXT NULL,
    value_decimal DECIMAL(18,6) NULL,
    value_json JSON NULL,
    PRIMARY KEY (product_id, attribute_id), KEY ix_pav_attribute (attribute_id, product_id),
    CONSTRAINT fk_pav_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_pav_attribute FOREIGN KEY (attribute_id) REFERENCES product_attributes(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE projects (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    slug VARCHAR(160) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    client_name VARCHAR(200) NULL,
    country_id BIGINT UNSIGNED NULL,
    location VARCHAR(255) NULL,
    completion_date DATE NULL,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'draft',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_projects_public_id (public_id), UNIQUE KEY uq_projects_slug (slug),
    KEY ix_projects_published (status_code, published_at, featured),
    CONSTRAINT fk_projects_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE project_sector (
    project_id BIGINT UNSIGNED NOT NULL,
    sector_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (project_id, sector_id), KEY ix_project_sector_sector (sector_id, project_id),
    CONSTRAINT fk_project_sector_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    CONSTRAINT fk_project_sector_sector FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE project_product (
    project_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (project_id, product_id), KEY ix_project_product_product (product_id, project_id),
    CONSTRAINT fk_project_product_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    CONSTRAINT fk_project_product_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE articles (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    slug VARCHAR(180) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    author_user_id BIGINT UNSIGNED NULL,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'draft',
    published_at DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_articles_public_id (public_id), UNIQUE KEY uq_articles_slug (slug),
    KEY ix_articles_published (status_code, published_at),
    CONSTRAINT fk_articles_author FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE article_categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    slug VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id), UNIQUE KEY uq_article_categories_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE article_category_article (
    article_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id), KEY ix_article_category_category (category_id, article_id),
    CONSTRAINT fk_aca_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    CONSTRAINT fk_aca_category FOREIGN KEY (category_id) REFERENCES article_categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE job_postings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    slug VARCHAR(160) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    country_id BIGINT UNSIGNED NULL,
    employment_type VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NULL,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'draft',
    closes_at DATETIME NULL,
    published_at DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_jobs_public_id (public_id), UNIQUE KEY uq_jobs_slug (slug),
    KEY ix_jobs_public_status (status_code, published_at, closes_at),
    CONSTRAINT fk_jobs_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE job_applications (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    job_posting_id BIGINT UNSIGNED NULL,
    applicant_name VARCHAR(160) NOT NULL,
    email VARCHAR(254) NOT NULL,
    phone VARCHAR(40) NULL,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'received',
    rating TINYINT UNSIGNED NULL,
    reviewer_user_id BIGINT UNSIGNED NULL,
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_applications_public_id (public_id),
    KEY ix_applications_pipeline (status_code, submitted_at), KEY ix_applications_job (job_posting_id, submitted_at),
    CONSTRAINT chk_application_rating CHECK (rating IS NULL OR rating BETWEEN 1 AND 5),
    CONSTRAINT fk_application_job FOREIGN KEY (job_posting_id) REFERENCES job_postings(id) ON DELETE RESTRICT,
    CONSTRAINT fk_application_reviewer FOREIGN KEY (reviewer_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE payment_methods (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(120) NOT NULL,
    method_type VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    provider_code VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NULL,
    requires_manual_verification TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    configuration JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_payment_methods_code (code), KEY ix_payment_methods_active (is_active, method_type)
) ENGINE=InnoDB;

CREATE TABLE country_payment_methods (
    country_id BIGINT UNSIGNED NOT NULL,
    payment_method_id BIGINT UNSIGNED NOT NULL,
    currency_id BIGINT UNSIGNED NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    configuration JSON NULL,
    PRIMARY KEY (country_id, payment_method_id, currency_id), KEY ix_country_payment_method (payment_method_id, country_id),
    CONSTRAINT fk_cpm_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    CONSTRAINT fk_cpm_method FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE RESTRICT,
    CONSTRAINT fk_cpm_currency FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE tax_jurisdictions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    country_id BIGINT UNSIGNED NOT NULL,
    code VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(120) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_tax_jurisdiction_code (country_id, code), KEY ix_tax_jurisdiction_active (country_id, is_active),
    CONSTRAINT fk_tax_jurisdiction_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE tax_rates (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    tax_jurisdiction_id BIGINT UNSIGNED NOT NULL,
    code VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    name VARCHAR(120) NOT NULL,
    rate DECIMAL(9,6) NOT NULL,
    applies_to VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'all',
    inclusive TINYINT(1) NOT NULL DEFAULT 0,
    valid_from DATE NOT NULL,
    valid_until DATE NULL,
    created_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_tax_rate_start (tax_jurisdiction_id, code, valid_from),
    KEY ix_tax_rates_effective (tax_jurisdiction_id, valid_from, valid_until),
    CONSTRAINT chk_tax_rate_range CHECK (rate >= 0 AND rate <= 1 AND (valid_until IS NULL OR valid_until >= valid_from)),
    CONSTRAINT fk_tax_rates_jurisdiction FOREIGN KEY (tax_jurisdiction_id) REFERENCES tax_jurisdictions(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE invoices (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    invoice_number VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    company_id BIGINT UNSIGNED NOT NULL,
    country_id BIGINT UNSIGNED NOT NULL,
    currency_id BIGINT UNSIGNED NOT NULL,
    quotation_id BIGINT UNSIGNED NULL,
    issue_date DATE NOT NULL,
    due_date DATE NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    tax_total DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total DECIMAL(15,2) NOT NULL,
    amount_paid DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'issued',
    payment_status VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'unpaid',
    tax_snapshot JSON NOT NULL,
    issued_by_user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_invoices_public_id (public_id), UNIQUE KEY uq_invoices_number (invoice_number),
    KEY ix_invoices_company_due (company_id, due_date), KEY ix_invoices_payment_status (payment_status, issue_date),
    KEY ix_invoices_country_date (country_id, issue_date),
    CONSTRAINT chk_invoice_amounts CHECK (subtotal >= 0 AND tax_total >= 0 AND total >= 0 AND amount_paid >= 0 AND amount_paid <= total),
    CONSTRAINT fk_invoices_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE RESTRICT,
    CONSTRAINT fk_invoices_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE RESTRICT,
    CONSTRAINT fk_invoices_currency FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE RESTRICT,
    CONSTRAINT fk_invoices_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE RESTRICT,
    CONSTRAINT fk_invoices_issuer FOREIGN KEY (issued_by_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE invoice_lines (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    invoice_id BIGINT UNSIGNED NOT NULL,
    line_number SMALLINT UNSIGNED NOT NULL,
    description VARCHAR(500) NOT NULL,
    quantity DECIMAL(15,3) NOT NULL,
    unit VARCHAR(32) NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    line_subtotal DECIMAL(15,2) NOT NULL,
    tax_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    line_total DECIMAL(15,2) NOT NULL,
    tax_snapshot JSON NOT NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_invoice_line (invoice_id, line_number),
    CONSTRAINT chk_invoice_line_amounts CHECK (quantity >= 0 AND unit_price >= 0 AND line_subtotal >= 0 AND tax_amount >= 0 AND line_total >= 0),
    CONSTRAINT fk_invoice_lines_invoice FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE payments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    public_id CHAR(26) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    invoice_id BIGINT UNSIGNED NOT NULL,
    payment_method_id BIGINT UNSIGNED NOT NULL,
    currency_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status_code VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'pending',
    external_reference VARCHAR(160) NULL,
    provider_reference VARCHAR(160) NULL,
    received_at DATETIME NULL,
    verified_at DATETIME NULL,
    verified_by_user_id BIGINT UNSIGNED NULL,
    verification_note VARCHAR(1000) NULL,
    idempotency_key VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NULL,
    metadata JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_payments_public_id (public_id), UNIQUE KEY uq_payments_idempotency (idempotency_key),
    KEY ix_payments_invoice_status (invoice_id, status_code), KEY ix_payments_provider_ref (payment_method_id, provider_reference),
    CONSTRAINT chk_payment_amount CHECK (amount > 0),
    CONSTRAINT fk_payments_invoice FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE RESTRICT,
    CONSTRAINT fk_payments_method FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE RESTRICT,
    CONSTRAINT fk_payments_currency FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE RESTRICT,
    CONSTRAINT fk_payments_verifier FOREIGN KEY (verified_by_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE payment_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    payment_id BIGINT UNSIGNED NOT NULL,
    event_type VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    provider_code VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin NULL,
    provider_event_id VARCHAR(160) NULL,
    payload JSON NOT NULL,
    received_at DATETIME(6) NOT NULL,
    processed_at DATETIME(6) NULL,
    processing_result VARCHAR(40) CHARACTER SET ascii COLLATE ascii_bin NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_payment_provider_event (provider_code, provider_event_id),
    KEY ix_payment_events_payment (payment_id, received_at), KEY ix_payment_events_unprocessed (processed_at, received_at),
    CONSTRAINT fk_payment_events_payment FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE translation_subjects (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    subject_type VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_translation_subject (subject_type, subject_id), KEY ix_translation_subject_lookup (subject_type, subject_id)
) ENGINE=InnoDB;

CREATE TABLE translations (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    translation_subject_id BIGINT UNSIGNED NOT NULL,
    language_id BIGINT UNSIGNED NOT NULL,
    field_name VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    value LONGTEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id), UNIQUE KEY uq_translation_field (translation_subject_id, language_id, field_name),
    KEY ix_translations_language (language_id, translation_subject_id),
    CONSTRAINT fk_translations_subject FOREIGN KEY (translation_subject_id) REFERENCES translation_subjects(id) ON DELETE CASCADE,
    CONSTRAINT fk_translations_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Sample bootstrap rows. All future market/method/tax additions are INSERTs, not schema changes.
INSERT INTO currencies (code, name, symbol, minor_unit, is_active) VALUES
('EGP','Egyptian Pound','EGP',2,1), ('SAR','Saudi Riyal','SAR',2,1);
INSERT INTO languages (code, name, native_name, text_direction, is_active) VALUES
('en','English','English','ltr',1), ('ar','Arabic','العربية','rtl',1);
INSERT INTO countries (iso2, iso3, name, dialing_code, default_currency_id, default_language_id, is_active)
SELECT 'EG','EGY','Egypt','+20',c.id,l.id,1 FROM currencies c JOIN languages l ON l.code='ar' WHERE c.code='EGP';
INSERT INTO countries (iso2, iso3, name, dialing_code, default_currency_id, default_language_id, is_active)
SELECT 'SA','SAU','Saudi Arabia','+966',c.id,l.id,1 FROM currencies c JOIN languages l ON l.code='ar' WHERE c.code='SAR';
INSERT INTO country_languages (country_id, language_id, is_default)
SELECT c.id,l.id,(l.code='ar') FROM countries c CROSS JOIN languages l WHERE c.iso2 IN ('EG','SA');
INSERT INTO country_currencies (country_id, currency_id, is_default)
SELECT c.id,cu.id,1 FROM countries c JOIN currencies cu ON cu.id=c.default_currency_id WHERE c.iso2 IN ('EG','SA');
INSERT INTO payment_methods (code,name,method_type,provider_code,requires_manual_verification,is_active) VALUES
('paymob_card','Paymob Card','gateway','paymob',0,1),
('tap_card','Tap Card','gateway','tap',0,1),
('instapay','InstaPay','instant_local',NULL,1,1),
('fawry','Fawry','instant_local','fawry',0,1),
('mada','Mada','instant_local','mada',0,1),
('stc_pay','STC Pay','wallet','stc_pay',0,1),
('bank_wire','Bank Wire','bank_transfer',NULL,1,1),
('letter_of_credit','Letter of Credit','trade_finance',NULL,1,1);
INSERT INTO tax_jurisdictions (country_id,code,name,is_active)
SELECT id,iso2,CONCAT(name,' standard VAT'),1 FROM countries WHERE iso2 IN ('EG','SA');
INSERT INTO tax_rates (tax_jurisdiction_id,code,name,rate,applies_to,inclusive,valid_from)
SELECT id,'standard_vat','Standard VAT',CASE code WHEN 'EG' THEN 0.140000 ELSE 0.150000 END,'all',0,'2026-01-01'
FROM tax_jurisdictions WHERE code IN ('EG','SA');
INSERT INTO country_payment_methods (country_id,payment_method_id,currency_id,is_active)
SELECT c.id,pm.id,cu.id,1 FROM countries c JOIN currencies cu ON cu.id=c.default_currency_id
JOIN payment_methods pm ON (c.iso2='EG' AND pm.code IN ('paymob_card','instapay','fawry','bank_wire','letter_of_credit'))
 OR (c.iso2='SA' AND pm.code IN ('tap_card','mada','stc_pay','bank_wire','letter_of_credit'));

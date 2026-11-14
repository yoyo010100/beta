<?php

use App\Http\Controllers\Admin\AnnouncementsController;
use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CareersController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\QuotationLeadController;
use App\Http\Controllers\Admin\TechnicalDownloadController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BETA Industrial Web Routes
|--------------------------------------------------------------------------
*/

// ─── LOCALE SWITCHER ────────────────────────────────────────────────────────
Route::get('/locale/{locale}', [FrontendController::class, 'switchLocale'])
    ->name('locale.switch')
    ->where('locale', 'ar|en')
    ->middleware('set.locale');

// ─── FRONTEND PUBLIC ROUTES (180 req/min per IP) ────────────────────────────
Route::middleware(['throttle:180,1'])->group(function () {
    Route::get('/', [FrontendController::class, 'home'])->name('home');
    Route::get('/services', [FrontendController::class, 'services'])->name('services');
    Route::get('/services/{slug}', [FrontendController::class, 'serviceDetail'])->name('service.detail');
    Route::get('/products', [FrontendController::class, 'products'])->name('products');
    Route::get('/products/{product:slug}', [FrontendController::class, 'productShow'])->name('products.show');
    Route::get('/products/{product:slug}/download-file', [FrontendController::class, 'productDownloadFile'])->name('products.download-file');
    Route::post('/quote/select-product/{product:slug}', [FrontendController::class, 'selectProductForQuote'])->name('quote.select-product');

    // Public announcements (published, active records only).
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement:public_id}', [AnnouncementController::class, 'show'])
        ->name('announcements.show')->whereUlid('announcement');

    // Legacy URLs kept alive for old links/bookmarks.
    Route::redirect('/news', '/announcements')->name('announcements');
    Route::redirect('/news/{announcement}', '/announcements/{announcement}')
        ->whereUlid('announcement');
    Route::redirect('/insights/{announcement}', '/announcements/{announcement}')
        ->name('insights.show')->whereUlid('announcement');
    Route::get('/careers', [FrontendController::class, 'careers'])->name('careers');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
    Route::get('/about', [FrontendController::class, 'about'])->name('about');
    Route::get('/certificates', [FrontendController::class, 'certificates'])->name('certificates.index');
    Route::get('/certificates/{certificate}/download', [FrontendController::class, 'certificateDownload'])->name('certificates.download');
    Route::get('/technical-downloads/{download}/download', [FrontendController::class, 'technicalDownload'])->name('technical-downloads.download');
    Route::get('/projects', [FrontendController::class, 'projects'])->name('projects');
});

// ─── PUBLIC FORM SUBMISSIONS (10/min per IP, per form + Honeypot + CAPTCHA) ──
Route::middleware(['throttle:guest-forms', 'antibot'])->group(function () {
    Route::post('/contact/quote', [QuoteController::class, 'store'])->name('quote.submit');
    Route::post('/careers/apply', [FrontendController::class, 'submitApplication'])->name('application.submit');
});

// ─── ADMIN AUTHENTICATION ────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ─── ADMIN PANEL (Protected) ─────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quotation Lead Engine (ULID `public_id` binding)
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [QuotationLeadController::class, 'index'])->name('index');
        Route::get('/{lead:public_id}', [QuotationLeadController::class, 'show'])->name('show')->whereUlid('lead');
        Route::post('/{lead:public_id}/quotation', [QuotationLeadController::class, 'storeQuotation'])->name('quotation.store')->whereUlid('lead');
        Route::patch('/{lead:public_id}/status', [QuotationLeadController::class, 'updateStatus'])->name('status.update')->whereUlid('lead');
    });

    // Product & Service Catalog
    // Product restore is registered under the `admin.products.*` name space so that
    // route('admin.products.restore', ...) in the admin product catalog resolves.
    Route::prefix('catalog/products')->name('products.')->group(function () {
        Route::post('{id}/restore', [CatalogController::class, 'restoreProduct'])->name('restore');
    });

    Route::prefix('catalog')->name('catalog.')->group(function () {
        // Products
        Route::get('/products', [CatalogController::class, 'productsIndex'])->name('products');
        Route::get('/products/create', [CatalogController::class, 'productCreate'])->name('products.create');
        Route::post('/products', [CatalogController::class, 'productStore'])->name('products.store');
        Route::get('/products/{product:public_id}/edit', [CatalogController::class, 'productEdit'])->name('products.edit')->whereUlid('product');
        Route::put('/products/{product:public_id}', [CatalogController::class, 'productUpdate'])->name('products.update')->whereUlid('product');
        Route::delete('/products/{product:public_id}', [CatalogController::class, 'productDestroy'])->name('products.destroy')->whereUlid('product');
        Route::post('/products/{product:public_id}/attachments', [CatalogController::class, 'productAttachmentStore'])->name('products.attachments.store')->whereUlid('product');

        // Services
        Route::get('/services', [CatalogController::class, 'servicesIndex'])->name('services');
        Route::get('/services/create', [CatalogController::class, 'serviceCreate'])->name('services.create');
        Route::post('/services', [CatalogController::class, 'serviceStore'])->name('services.store');
        Route::get('/services/{service:public_id}/edit', [CatalogController::class, 'serviceEdit'])->name('services.edit')->whereUlid('service');
        Route::put('/services/{service:public_id}', [CatalogController::class, 'serviceUpdate'])->name('services.update')->whereUlid('service');
        Route::delete('/services/{service:public_id}', [CatalogController::class, 'serviceDestroy'])->name('services.destroy')->whereUlid('service');
        Route::post('/services/{service:public_id}/attachments', [CatalogController::class, 'serviceAttachmentStore'])->name('services.attachments.store')->whereUlid('service');

        // Attachment removal
        Route::delete('/attachments/{attachment:public_id}', [CatalogController::class, 'attachmentDestroy'])->name('attachments.destroy')->whereUlid('attachment');
    });

    // Announcements CMS
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AnnouncementsController::class, 'index'])->name('index');
        Route::get('/create', [AnnouncementsController::class, 'create'])->name('create');
        Route::post('/', [AnnouncementsController::class, 'store'])->name('store');
        Route::get('/{announcement:public_id}/edit', [AnnouncementsController::class, 'edit'])->name('edit')->whereUlid('announcement');
        Route::put('/{announcement:public_id}', [AnnouncementsController::class, 'update'])->name('update')->whereUlid('announcement');
        Route::delete('/{announcement:public_id}', [AnnouncementsController::class, 'destroy'])->name('destroy')->whereUlid('announcement');
        Route::post('/{announcement:public_id}/attachments', [AnnouncementsController::class, 'attachmentStore'])->name('attachments.store')->whereUlid('announcement');
    });

    // Technical Downloads
    Route::prefix('technical-downloads')->name('technical-downloads.')->group(function () {
        Route::get('/', [TechnicalDownloadController::class, 'index'])->name('index');
        Route::get('/create', [TechnicalDownloadController::class, 'create'])->name('create');
        Route::post('/', [TechnicalDownloadController::class, 'store'])->name('store');
        Route::get('/{technical_download}/edit', [TechnicalDownloadController::class, 'edit'])->name('edit');
        Route::put('/{technical_download}', [TechnicalDownloadController::class, 'update'])->name('update');
        Route::delete('/{technical_download}', [TechnicalDownloadController::class, 'destroy'])->name('destroy');
        Route::get('/{technical_download}/download', [TechnicalDownloadController::class, 'download'])->name('download');
        Route::post('/reorder', [TechnicalDownloadController::class, 'reorder'])->name('reorder');
        Route::post('/{technical_download}/move', [TechnicalDownloadController::class, 'move'])->name('move');
    });

    // Certificates
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::get('/create', [CertificateController::class, 'create'])->name('create');
        Route::post('/', [CertificateController::class, 'store'])->name('store');
        Route::get('/{certificate}/edit', [CertificateController::class, 'edit'])->name('edit');
        Route::put('/{certificate}', [CertificateController::class, 'update'])->name('update');
        Route::delete('/{certificate}', [CertificateController::class, 'destroy'])->name('destroy');
        Route::get('/{certificate}/download', [CertificateController::class, 'download'])->name('download');
        Route::post('/reorder', [CertificateController::class, 'reorder'])->name('reorder');
        Route::post('/{certificate}/move', [CertificateController::class, 'move'])->name('move');
    });

    // Careers ATS
    Route::prefix('careers')->name('careers.')->group(function () {
        Route::get('/applications', [CareersController::class, 'index'])->name('index');
        Route::get('/applications/{application:public_id}', [CareersController::class, 'show'])->name('show')->whereUlid('application');
        Route::patch('/applications/{application:public_id}/rating', [CareersController::class, 'updateRating'])->name('rating')->whereUlid('application');
        Route::patch('/applications/{application:public_id}/status', [CareersController::class, 'updateStatus'])->name('status')->whereUlid('application');
        Route::get('/postings', [CareersController::class, 'postingsIndex'])->name('postings');
        Route::post('/postings', [CareersController::class, 'postingStore'])->name('postings.store');
        Route::patch('/postings/{posting:public_id}/toggle', [CareersController::class, 'postingToggle'])->name('postings.toggle')->whereUlid('posting');
    });

    // Secure Attachment Downloads (Signed URLs)
    Route::get('/attachments/{attachment:public_id}/download', [AttachmentController::class, 'download'])
        ->name('attachments.download')->whereUlid('attachment');
    Route::get('/attachments/{attachment:public_id}/signed-url', [AttachmentController::class, 'getSignedUrl'])
        ->name('attachments.signed-url')->whereUlid('attachment');
});

// Convenience redirect
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

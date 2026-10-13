<?php

use App\Http\Controllers\Admin\AnnouncementsController;
use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CareersController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuotationLeadController;
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
    Route::get('/products', [FrontendController::class, 'products'])->name('products');
    Route::get('/news', [FrontendController::class, 'announcements'])->name('announcements');
    Route::get('/news/{announcement:public_id}', [FrontendController::class, 'announcementShow'])->name('announcements.show')->whereUlid('announcement');
    Route::get('/insights/{announcement:public_id}', [FrontendController::class, 'insightDetail'])->name('insights.show')->whereUlid('announcement');
    Route::get('/careers', [FrontendController::class, 'careers'])->name('careers');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
    Route::view('/about', 'frontend.about')->name('about');
    Route::view('/projects', 'frontend.projects')->name('projects');
});

// ─── PUBLIC FORM SUBMISSIONS (15/hour per IP + Honeypot + Math CAPTCHA) ─────
Route::middleware(['throttle:15,3600', 'antibot'])->group(function () {
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

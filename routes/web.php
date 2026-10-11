<?php

use App\Http\Controllers\Admin\AnnouncementsController;
use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CareersController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuotationLeadController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BETA Industrial Web Routes
|--------------------------------------------------------------------------
*/

// ─── LOCALE SWITCHER ────────────────────────────────────────────────────────
Route::get('/locale/{locale}', [FrontendController::class, 'switchLocale'])
    ->name('locale.switch')
    ->where('locale', 'ar|en');

// ─── FRONTEND PUBLIC ROUTES (Rate Limited) ──────────────────────────────────
Route::middleware(['throttle:public-get'])->group(function () {
    Route::get('/', [FrontendController::class, 'home'])->name('home');
    Route::get('/services', [FrontendController::class, 'services'])->name('services');
    Route::get('/products', [FrontendController::class, 'products'])->name('products');
    Route::get('/news', [FrontendController::class, 'announcements'])->name('announcements');
    Route::get('/news/{announcement:slug}', [FrontendController::class, 'announcementShow'])->name('announcements.show');
    Route::get('/insights/{announcement:slug}', [FrontendController::class, 'insightDetail'])->name('insights.show');
    Route::get('/careers', [FrontendController::class, 'careers'])->name('careers');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
    Route::view('/about', 'frontend.about')->name('about');
    Route::view('/projects', 'frontend.projects')->name('projects');
});

// ─── PUBLIC FORM SUBMISSIONS (Honeypot + Math CAPTCHA + Rate Limit) ─────────
Route::middleware(['throttle:public-forms', 'antibot'])->group(function () {
    Route::post('/contact/quote', [FrontendController::class, 'submitQuote'])->name('quote.submit');
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

    // Quotation Lead Engine
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [QuotationLeadController::class, 'index'])->name('index');
        Route::get('/{lead:public_id}', [QuotationLeadController::class, 'show'])->name('show');
        Route::post('/{lead:public_id}/quotation', [QuotationLeadController::class, 'storeQuotation'])->name('quotation.store');
        Route::patch('/{lead:public_id}/status', [QuotationLeadController::class, 'updateStatus'])->name('status.update');
    });

    // Product & Service Catalog
    Route::prefix('catalog')->name('catalog.')->group(function () {
        // Products
        Route::get('/products', [CatalogController::class, 'productsIndex'])->name('products');
        Route::get('/products/create', [CatalogController::class, 'productCreate'])->name('products.create');
        Route::post('/products', [CatalogController::class, 'productStore'])->name('products.store');
        Route::get('/products/{product:public_id}/edit', [CatalogController::class, 'productEdit'])->name('products.edit');
        Route::put('/products/{product:public_id}', [CatalogController::class, 'productUpdate'])->name('products.update');
        Route::delete('/products/{product:public_id}', [CatalogController::class, 'productDestroy'])->name('products.destroy');

        // Services
        Route::get('/services', [CatalogController::class, 'servicesIndex'])->name('services');
        Route::post('/services', [CatalogController::class, 'serviceStore'])->name('services.store');
        Route::delete('/services/{service:public_id}', [CatalogController::class, 'serviceDestroy'])->name('services.destroy');
    });

    // Announcements CMS
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AnnouncementsController::class, 'index'])->name('index');
        Route::get('/create', [AnnouncementsController::class, 'create'])->name('create');
        Route::post('/', [AnnouncementsController::class, 'store'])->name('store');
        Route::get('/{announcement:public_id}/edit', [AnnouncementsController::class, 'edit'])->name('edit');
        Route::put('/{announcement:public_id}', [AnnouncementsController::class, 'update'])->name('update');
        Route::delete('/{announcement:public_id}', [AnnouncementsController::class, 'destroy'])->name('destroy');
    });

    // Careers ATS
    Route::prefix('careers')->name('careers.')->group(function () {
        Route::get('/applications', [CareersController::class, 'index'])->name('index');
        Route::get('/applications/{application:public_id}', [CareersController::class, 'show'])->name('show');
        Route::patch('/applications/{application:public_id}/rating', [CareersController::class, 'updateRating'])->name('rating');
        Route::patch('/applications/{application:public_id}/status', [CareersController::class, 'updateStatus'])->name('status');
        Route::get('/postings', [CareersController::class, 'postingsIndex'])->name('postings');
        Route::post('/postings', [CareersController::class, 'postingStore'])->name('postings.store');
        Route::patch('/postings/{posting:public_id}/toggle', [CareersController::class, 'postingToggle'])->name('postings.toggle');
    });

    // Secure Attachment Downloads (Signed URLs)
    Route::get('/attachments/{attachment:public_id}/download', [AttachmentController::class, 'download'])
        ->name('attachments.download');
    Route::get('/attachments/{attachment:public_id}/signed-url', [AttachmentController::class, 'getSignedUrl'])
        ->name('attachments.signed-url');
});

// Convenience redirect
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

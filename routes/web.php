<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TransactionController;



Route::get('/login', [\App\Http\Controllers\Auth\UserAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\UserAuthController::class, 'login'])->name('login.post');

// Rute User Area
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{event}', [EventController::class,'show'])->name('events.show');
Route::get('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// Google OAuth SSO
Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
// Reviews & Ratings
Route::post('/event/{event}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('events.reviews.store');
// Organizer Profile
Route::get('/organizer/{partner}', [\App\Http\Controllers\OrganizerController::class, 'show'])->name('organizer.show');
Route::prefix('organization')->name('organization.')->group(function () {

    // Register
    Route::get('/register', [App\Http\Controllers\Organization\AuthController::class, 'showRegister'])
        ->name('register');
    Route::post('/register', [App\Http\Controllers\Organization\AuthController::class, 'register'])
        ->name('register.store');

    // Login
    Route::get('/login', [App\Http\Controllers\Organization\AuthController::class, 'showLogin'])
        ->name('login');
    Route::post('/login', [App\Http\Controllers\Organization\AuthController::class, 'login'])
        ->name('login.store');

    // Protected Routes
    Route::middleware(['organization'])->group(function () {
        // Logout
        Route::post('/logout', [App\Http\Controllers\Organization\AuthController::class, 'logout'])
            ->name('logout');

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Organization\DashboardController::class, 'index'])
            ->name('dashboard');

        // Transactions Report
        Route::get('/transactions', [App\Http\Controllers\Organization\TransactionController::class, 'index'])
            ->name('transactions.index');

        // Events Resource
        Route::resource('events', App\Http\Controllers\Organization\EventController::class);
    });

});

Route::get('/organization/{organization}', [\App\Http\Controllers\OrganizerController::class, 'showOrganization'])->name('organization.profile');

//Route::get('/checkout', [EventController::class,'checkout'])->name('checkout');
//Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

/*Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Catatan: Dashboard & Login Auth di kemudian hari akan menempati blok ini juga
    Route::resource('events', EventAdminController::class);
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/transactions', [DashboardController::class,'indexTransaction'])->name('transactions.index');
    // dan seterusnya...
    Route::resource('partners', PartnerController::class);
    Route::resource('categories', CategoryController::class);
*/
// Grouping untuk URL berawalan /admin
// Grouping utama untuk prefix 'admin' dan name 'admin.'
Route::prefix('admin')->name('admin.')->group(function () {
    // 1. Rute Publik (Tidak memerlukan middleware auth)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // 2. Rute Terproteksi (Memerlukan middleware auth & admin)
    Route::middleware(['auth', 'admin'])->group(function () { 
        
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Opsional, jika ingin akses via /admin/dashboard
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        // Resources (CRUD)
        Route::resource('events', EventAdminController::class);
        //Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
        Route::resource('partners', PartnerController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
        
    });

});

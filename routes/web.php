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



Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rute User Area
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{event}', [EventController::class,'show'])->name('events.show');
Route::get('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);


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
        
    });

});

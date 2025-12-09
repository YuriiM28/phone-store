<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ManagerCatalogController;
use App\Http\Controllers\ManagerPhoneController;
use App\Http\Controllers\ManagerPromocodeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromocodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PhoneController::class, 'index']);
Route::get('/phones', [PhoneController::class, 'index'])->name('phones.index');
Route::get('/phones/{phone:slug}', [PhoneController::class, 'show'])->name('phones.show');

Route::prefix('orders')->group(function () {
    Route::get('/create/{phoneSlug}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/store/{phoneSlug}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/success/{orderNumber}', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('reviews')->group(function () {
    Route::get('/create/{phoneSlug}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/store/{phoneSlug}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('orders')->group(function () {
    Route::get('/create/{phoneSlug}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/store/{phoneSlug}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/success/{orderNumber}', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
});

Route::prefix('manager')->name('manager.')->middleware(['auth', 'manager'])->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'phones_count' => \App\Models\Phone::count(),
            'orders_count' => \App\Models\Order::count(),
            'users_count' => \App\Models\User::count(),
            'reviews_count' => \App\Models\Review::count(),
        ];
        return view('manager.dashboard', compact('stats'));
    })->name('dashboard');

    Route::resource('phones', ManagerPhoneController::class);

    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::get('/brands', [ManagerCatalogController::class, 'brandsIndex'])->name('brands');
        Route::post('/brands', [ManagerCatalogController::class, 'brandStore'])->name('brands.store');
        Route::put('/brands/{brand}', [ManagerCatalogController::class, 'brandUpdate'])->name('brands.update');
        Route::delete('/brands/{brand}', [ManagerCatalogController::class, 'brandDestroy'])->name('brands.destroy');

        Route::get('/categories', [ManagerCatalogController::class, 'categoriesIndex'])->name('categories');
        Route::post('/categories', [ManagerCatalogController::class, 'categoryStore'])->name('categories.store');
        Route::put('/categories/{category}', [ManagerCatalogController::class, 'categoryUpdate'])->name('categories.update');
        Route::delete('/categories/{category}', [ManagerCatalogController::class, 'categoryDestroy'])->name('categories.destroy');
    });
});

Route::middleware('auth')->prefix('favorites')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/{phone}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

Route::post('/promocode/validate', [PromocodeController::class, 'validatePromo'])->name('promocode.validate');

Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::resource('promocodes', ManagerPromocodeController::class);
    Route::get('/promocodes/generate/code', [ManagerPromocodeController::class, 'generateCode'])->name('promocodes.generate.code');
});
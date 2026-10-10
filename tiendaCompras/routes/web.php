<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/productos', [StorefrontController::class, 'products'])->name('catalog');
Route::get('/sucursales', [StorefrontController::class, 'stores'])->name('stores');
Route::get('/nosotros', [StorefrontController::class, 'about'])->name('about');
Route::get('/contacto', [StorefrontController::class, 'contact'])->name('contact');
Route::post('/contacto', [StorefrontController::class, 'storeContact'])->name('contact.store');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Volt::route('categories', 'categories.index')->name('categories.index');

    Route::resource('products', ProductController::class)->except('show');
    Route::resource('batches', BatchController::class)->except(['show', 'edit', 'update']);
    Route::get('batches/{batch}/barcode', [BatchController::class, 'barcode'])->name('batches.barcode');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
});

require __DIR__.'/auth.php';

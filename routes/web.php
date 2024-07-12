<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RubriqueController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    
    Route::resource('rubriques', RubriqueController::class)->names([
        'index' => 'rubriques.index',
        'create' => 'rubriques.create',
        'store' => 'rubriques.store',
        'show' => 'rubriques.show',
        'edit' => 'rubriques.edit',
        'update' => 'rubriques.update',
        'destroy' => 'rubriques.destroy',
    ]);
    Route::resource('products', ProductController::class)->names([
        'index' => 'products.index',
        'create' => 'products.create',
        'store' => 'products.store',
        'show' => 'products.show',
        'edit' => 'products.edit',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);
    Route::resource('suppliers', SupplierController::class)->names([
        'index' => 'suppliers.index',
        'create' => 'suppliers.create',
        'store' => 'suppliers.store',
        'show' => 'suppliers.show',
        'edit' => 'suppliers.edit',
        'update' => 'suppliers.update',
        'destroy' => 'suppliers.destroy',
    ]);
    Route::resource('services', ServiceController::class)->names([
        'index' => 'services.index',
        'create' => 'services.create',
        'store' => 'services.store',
        'show' => 'services.show',
        'edit' => 'services.edit',
        'update' => 'services.update',
        'destroy' => 'services.destroy',
    ]);
});


/*
Route::get('/', function () {
    return Inertia::render('Test', []);
})->name('dashboard');

Route::get('/side-menu-light-dashboard-overview-2.html', function () {
    return Inertia::render('Test', []);
});
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

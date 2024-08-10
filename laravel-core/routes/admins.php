<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\RubriqueController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['auth'])->group(function () {
    Route::middleware('permission:dashboard_consult')->controller(DashboardController::class)->group(function(){
        Route::get('', "index")->name('dashboard');
    });
    Route::middleware('permission:stock_consult')->prefix("stock")->controller(StockController::class)->group(function(){
        Route::get('', "index")->name('stock');
        Route::get('{product}/notes', "stock_note")->middleware('permission:stock_consult')->name('stock_note');
    });
    Route::middleware('permission:costs_consult')->prefix("costs")->controller(CostController::class)->group(function(){
        Route::get('', "index")->name('costs');
        Route::post('', "calcule")->name('costs_calculate');
    });
    Route::middleware('permission:inboundsRegister_consult')->prefix("inbounds/register")->controller(InboundController::class)->group(function(){
        Route::get('', "inboundsRegister")->name('inboundsRegister');
        Route::post('', "inboundsRegister_calculate")->name('inboundsRegister_calculate');
    });
    Route::middleware('permission:outboundsRegister_consult')->prefix("outbounds/register")->controller(OutboundController::class)->group(function(){
        Route::get('', "outboundsRegister")->name('outboundsRegister');
        Route::post('', "outboundsRegister_calculate")->name('outboundsRegister_calculate');
    });
    
    Route::middleware('permission:inbounds_consult')->prefix("inbounds")->controller(InboundController::class)->group(function(){
        Route::get('', "index")->name('inbounds');
        Route::get('create', "create")->middleware('permission:inbounds_create')->name('inbounds_create');
        Route::post('create', "store")->middleware('permission:inbounds_create');

        Route::get('{inbound}/notes/command', "command_note")->middleware('permission:inbounds_consult')->name('inbounds_command_note');
        Route::get('{inbound}/notes/delivery', "delivery_note")->middleware('permission:inbounds_consult')->name('inbounds_delivery_note');
        #Route::get('{inbound}/edit', "edit")->middleware('permission:inbounds_edit')->name('inbounds_edit');
        #Route::put('{inbound}/edit', "update")->middleware('permission:inbounds_edit');
        Route::get('{inbound}/products/add', "add_products")->middleware('permission:inbounds_create')->name('inbounds_add_products');
        Route::post('{inbound}/products/add', "store_products")->middleware('permission:inbounds_create');
        Route::delete('{inbound}/delete', "destroy")->middleware('permission:inbounds_delete')->name('inbounds_delete');
    });
    
    Route::middleware('permission:outbounds_consult')->prefix("outbounds")->controller(OutboundController::class)->group(function(){
        Route::get('', "index")->name('outbounds');
        Route::get('create', "create")->middleware('permission:outbounds_create')->name('outbounds_create');
        Route::post('create', "store")->middleware('permission:outbounds_create');

        Route::get('{outbound}/notes/exit', "exit_note")->middleware('permission:outbounds_consult')->name('outbounds_exit_note');
        #Route::get('{outbound}/edit', "edit")->middleware('permission:outbounds_edit')->name('outbounds_edit');
        #Route::put('{outbound}/edit', "update")->middleware('permission:outbounds_edit');
        Route::get('{outbound}/products/add', "add_products")->middleware('permission:outbounds_create')->name('outbounds_add_products');
        Route::post('{outbound}/products/add', "store_products")->middleware('permission:outbounds_create');
        Route::delete('{outbound}/delete', "destroy")->middleware('permission:outbounds_delete')->name('outbounds_delete');
    });

    Route::middleware('permission:users_consult')->prefix("users")->controller(UserController::class)->group(function(){
        Route::get('', "index")->name('users');
        Route::post('', "store")->middleware('permission:users_create')->name('users_create');
        Route::put('{user}/edit', "update")->middleware('permission:users_edit')->name('users_edit');
        Route::delete('{user}/delete', "destroy")->middleware('permission:users_delete')->name('users_delete');
    });
    Route::middleware('permission:rubriques_consult')->prefix("rubriques")->controller(RubriqueController::class)->group(function(){
        Route::get('', "index")->name('rubriques');
        Route::post('', "store")->middleware('permission:rubriques_create')->name('rubriques_create');
        Route::put('{rubrique}/edit', "update")->middleware('permission:rubriques_edit')->name('rubriques_edit');
        Route::delete('{rubrique}/delete', "destroy")->middleware('permission:rubriques_delete')->name('rubriques_delete');
    });
    Route::middleware('permission:products_consult')->prefix("products")->controller(ProductController::class)->group(function(){
        Route::get('', "index")->name('products');
        Route::post('', "store")->middleware('permission:products_create')->name('products_create');
        Route::put('{product}/edit', "update")->middleware('permission:products_edit')->name('products_edit');
        Route::delete('{product}/delete', "destroy")->middleware('permission:products_delete')->name('products_delete');
    });
    Route::middleware('permission:suppliers_consult')->prefix("suppliers")->controller(SupplierController::class)->group(function(){
        Route::get('', "index")->name('suppliers');
        Route::post('', "store")->middleware('permission:suppliers_create')->name('suppliers_create');
        Route::put('{supplier}/edit', "update")->middleware('permission:suppliers_edit')->name('suppliers_edit');
        Route::delete('{supplier}/delete', "destroy")->middleware('permission:suppliers_delete')->name('suppliers_delete');
    });
    Route::middleware('permission:services_consult')->prefix("services")->controller(ServiceController::class)->group(function(){
        Route::get('', "index")->name('services');
        Route::post('', "store")->middleware('permission:services_create')->name('services_create');
        Route::put('{service}/edit', "update")->middleware('permission:services_edit')->name('services_edit');
        Route::delete('{service}/delete', "destroy")->middleware('permission:services_delete')->name('services_delete');
    });
    Route::middleware('permission:settings_consult')->prefix("settings")->controller(SettingsController::class)->group(function(){
        Route::get('', "index")->name('settings');
        Route::post('edit', "edit")->name('settings_edit');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});
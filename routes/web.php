<?php

use App\Http\Controllers\CollectionWebController;
use App\Http\Controllers\ColorWebController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderWebController;
use App\Http\Controllers\OtherWebController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductVariantWebController;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\StatusWebController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\TypeWebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserWebController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// view
Route::get('/login', function() {
    return view('auth.login');
})->name('login');

Route::get('/verify', function() {
    return view('auth.verif');
})->name('verify');

Route::get('/other', [OtherWebController::class, 'counter'])->name('other.other');

// controller base
Route::post('/auth-verif', [UserWebController::class, 'userRequestOtpBlade'])->name('auth-verif');
Route::post('/auth-verify-otp', [UserWebController::class, 'userVerifyOtpBlade'])->name('auth-verify-otp');

Route::get('/products', [ProductWebController::class, 'getProducts'])->name('products.index');
Route::get('/products/{productId}', [ProductWebController::class, 'showProductDetail'])->name('product.detail');

Route::get('/collections', [CollectionWebController::class, 'getCollections'])->name('collections.index');
Route::get('/collections/{collectionId}/products', [CollectionWebController::class, 'getProductsPerCollection'])->name('collections.products');

Route::get('/orders', [OrderWebController::class, 'showOrders'])->name('orders.index');

Route::middleware(['is_admin_web'])->group(function() {

    // view

    Route::get('/', function() {
        return view('components.welcome');
    })->name('admin');

    Route::get('/products-view', function() {
        return view('components.products.list_products');
    })->name('products');

    Route::get('/collections-view', function() {
        return view('components.collections.list_collection');
    })->name('collections');

    Route::get('/create-collection', function() {
        return view('components.collections.create_collection');
    })->name('create-collection');

    Route::get('/orders-view', function() {
        return view('components.orders.list_orders');
    })->name('orders');

    Route::get('/create-product', function() {
        return view('components.products.create_product');
    })->name('create-product');

    Route::get('/products/{productId}/variants/create', [ProductVariantWebController::class, 'showCreateForm'])->name('variant.create.form');

    Route::get('/detail-product', function() {
        return view('components.products.detail_product');
    })->name('detail-product');

    Route::get('/detail-variant', function() {
        return view('components.products.detail_product_variant');
    })->name('detail-variant');

    Route::get('/detail-order', function() {
        return view('components.orders.detail_order');
    })->name('detail-order');

    Route::get('/status-create', function() {
        return view('components.other.components.status.create_status');
    })->name('create-status');

    Route::get('/type-create', function() {
        return view('components.other.components.type.create_type');
    })->name('create-type');

    Route::get('/color-create', function() {
        return view('components.other.components.color.create_color');
    })->name('create-color');

    // controller base
    Route::delete('/logout', [UserWebController::class, 'logout'])->name('logout');

    Route::post('/image/upload', [ImageController::class, 'uploadImage'])->name('image.upload');

    Route::post('/products/create', [ProductWebController::class, 'createProduct'])->name('products.create');
    Route::delete('/products/{productId}/delete', [ProductWebController::class, 'destroy'])->name('products.destroy');

    Route::post('/collections/create', [CollectionWebController::class, 'createCollection'])->name('collections.create');
    Route::delete('/collections/{collectionId}/delete', [CollectionWebController::class, 'deleteCollection'])->name('collections.delete');

    Route::get('/orders/{orderId}', [OrderWebController::class, 'showDetail'])->name('order.detail');
    Route::post('/orders/{orderId}/update-status', [OrderWebController::class, 'updateStatus'])->name('order.updateStatus');

    Route::post('/products/{productId}/variants/create', [ProductVariantWebController::class, 'createProductVariant'])->name('variant.create');
    Route::patch('/products/{productId}/variants/{variantId}/update', [ProductVariantWebController::class, 'updateProductVariant'])->name('variant.update');
    Route::delete('/products/{productId}/variants/{variantId}/delete', [ProductVariantWebController::class, 'deleteProductVariant'])->name('variant.delete');
    
    Route::controller(TypeWebController::class)->group(function() {
        Route::post('/type/create', 'createType')->name('type.create');
        Route::get('/type/get', 'getTypes')->name('type.get');
        Route::delete('/type/{typeId}/delete', 'deleteType')->name('type.delete');
    });

    Route::controller(ColorWebController::class)->group(function() {
        Route::post('/color/create', 'createColor')->name('color.create');
        Route::get('/colors/get', 'getColors')->name('colors.get');
        Route::delete('/color/{colorId}/delete', 'deleteColor')->name('color.delete');
    });

    Route::controller(StatusWebController::class)->group(function() {
        Route::post('/status/create', 'createStatus')->name('status.create');
        Route::get('/status/get', 'getStatuses')->name('status.get');
        Route::delete('/status{statusId}/delete', 'deleteStatus')->name('status.delete');
    });


});

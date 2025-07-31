<?php

use App\Http\Controllers\CollectionWebController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderWebController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductWebController;
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

// controller 
Route::controller(UserWebController::class)->group(function() {
    Route::post('/auth-verif', 'userRequestOtpBlade')->name('auth-verif');
    Route::post('/auth-verify-otp', 'userVerifyOtpBlade')->name('auth-verify-otp');
});

// Route::post('/image/upload', [ImageController::class, 'uploadImage'])->name('image.upload');

Route::post('/image/upload', [ImageController::class, 'uploadImage'])->name('image.upload');

Route::controller(ProductWebController::class)->group(function() {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/{productId}', 'showProductDetail')->name('product.detail');
    Route::delete('/products/{productId}', 'destroy')->name('products.destroy');
});

Route::controller(CollectionWebController::class)->group(function() {
    Route::get('/collections', 'getCollections')->name('collections.index');
    Route::post('/collections/create', 'createCollection')->name('collections.create');
    Route::get('/collections/{collectionId}/delete', 'deleteCollection')->name('collections.delete');
});

Route::controller(OrderWebController::class)->group(function() {
    Route::get('/orders', 'showOrders')->name('orders.index');
    Route::get('/orders/{orderId}', 'showDetail')->name('order.detail');
    Route::post('/orders/{orderId}/update-status', 'updateStatus')->name('order.updateStatus');
});

Route::controller(ProductVariantController::class)->group(function() {
    Route::post('/products/{productId}/variants/create', 'createProductVariant')->name('variant.create');
    Route::post('/products/{productId}/variants/update', 'updateProductVariant')->name('variant.update');
    Route::post('/products/{productId}/variants/delete', 'deleteProductVariant')->name('variant.delete');
});


Route::middleware(['auth:api'])->group(function() {
    Route::middleware(['is_admin_web'])->group(function() {
        Route::get('/', function() {
            return view('components.welcome');
        })->name('admin');

        Route::get('/products', function() {
            return view('components.products.list_products');
        })->name('products');

        Route::get('/collections', function() {
            return view('components.collections.list_collection');
        })->name('collections');

        Route::get('/create-collection', function() {
            return view('components.collections.create_collection');
        })->name('create-collection');

        Route::get('/orders', function() {
            return view('components.orders.list_orders');
        })->name('orders');

        Route::get('/create-product', function() {
            return view('components.products.create_product');
        })->name('create-product');

        Route::get('/create-variant', function() {
            return view('components.products.create_product_variant');
        })->name('create-variant');

        Route::get('/detail-product', function() {
            return view('components.products.detail_product');
        })->name('detail-product');

        Route::get('/detail-variant', function() {
            return view('components.products.detail_product_variant');
        })->name('detail-variant');

        Route::get('/detail-order', function() {
            return view('components.orders.detail_order');
        })->name('detail-order');
    });
});
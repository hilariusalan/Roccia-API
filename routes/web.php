<?php

use App\Http\Controllers\CollectionWebController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderWebController;
use App\Http\Controllers\ProductWebController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', function() {
    return view('auth.login');
})->name('auth.login');

Route::post('/verification', function() {
    return view('auth.verif');
})->name('auth.verif');

Route::get('/admin', function() {
    return view('components.welcome');
})->name('admin');

Route::get('/products', function() {
    return view('components.products.list_products');
})->name('products');

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

Route::middleware(['auth:api'])->group(function() {
    Route::middleware(['is_admin'])->group(function() {
        
    });
});





// Route::get('/admin', function () {
//     return view('welcome');
// });

// Route::controller(CollectionWebController::class)->group(function() {
//     Route::post('/admin/create-collection', 'createCollection')->name('collections.components.add_collection');
//     Route::get('/admin/collections', 'getCollections')->name('collections.components.list_collections');
// });

// Route::get('/admin/orders', [OrderWebController::class, 'getOrders'])->name('orders.components.list_orders');
// Route::get('/admin/orders/{orderId}', function ($orderId) {
//     return view('orders.components.detail-order', ['orderId' => $orderId]);
// });

// Route::get('/admin/products', [ProductWebController::class, 'getProducts'])->name('products.components.list_products');
// Route::get('/products/create', function () {
//     return view('products.components.add-product');
// });
// Route::get('/products/{productId}/edit', function ($productId) {
//     return view('products.components.update-product', compact('productId'));
// });
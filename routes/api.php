<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BillingAddressController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\NameController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Resources\ColorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(UserController::class)->group(function() {
    Route::post('/auth/request-otp', 'userRequestOtp');
    Route::post('/auth/verify-otp', 'userVerifyOtp');
});

Route::controller(ProductController::class)->group(function() {
    Route::get('/products', 'getProducts');
    Route::get('/collections/{collectionId}/products', 'getProductsPerCollection')->where('collectionId', '[0-9]+');
});

Route::controller(TypeController::class)->group(function() {
    Route::post('/products/types/create', 'createType');
    Route::get('/products/types', 'getTypes');
});

Route::controller(OrderController::class)->group(function() {
    Route::get('/users/orders', 'getUserOrders');
    Route::get('/orders/{orderId}', 'getOrderDetail')->where('orderId', '[0-9]+');
});

Route::get('/collections', [CollectionController::class, 'getCollections']);
Route::get('/names', [NameController::class, 'getAllProductCollectionNames']);
Route::get('/colors', [ColorResource::class, 'getColors']);

Route::middleware(['auth:api'])->group(function() {
    Route::post('/billing-addresses/create', [BillingAddressController::class, 'createBillingAddress']);

    Route::controller(UserController::class)->group(function() {
        Route::get('/users', 'getUserData');
        Route::put('/users/update', 'updateUser');
        Route::delete('/logout', 'logoutUser');
    });

    Route::controller(AddressController::class)->group(function() {
        Route::get('/users/addresses', 'getUserAddress');
        Route::post('/users/address/create', 'createAddress');
        Route::put('/users/address/{addressId}/edit', 'updateUserAddress')->where('addressId', '[0-9]+');
        Route::delete('/users/address/{addressId}/delete', 'deleteUserAddress')->where('addressId', '[0-9]+'); 
    });

    Route::controller(OrderController::class)->group(function() {
        Route::get('/users/orders', 'getUserOrders');
        Route::get('/orders/{orderId}', 'getOrderDetail')->where('orderId', '[0-9]+');
        Route::post('/orders/create', 'createNewOrder');
    });

    // admin required
    Route::middleware(['is_admin'])->group(function() {
        Route::controller(ProductController::class)->group(function() {
            Route::post('/products/create', 'createProduct');
            Route::put('/products/{productId}/update', 'updateProduct')->where('productId', '[0-9]+');
            Route::delete('/products/{productId}/delete', 'deleteProduct')->where('productId', '[0-9]+');
        });

        Route::controller(CollectionController::class)->group(function() {
            Route::post('/collections/create', 'createCollection');
            Route::put('/collections/{collectionId}/update', 'updateCollection')->where('collectionId', '[0-9]+');
            Route::delete('/collections/{collectionId}/delete', 'deleteCollection')->where('collectionId', '[0-9]+');
        });

        Route::controller(OrderController::class)->group(function() {
            Route::get('/orders', 'getOrders');
        });

    });
});

<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BillingAddressController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NameController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\StatusController;
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
    Route::post('/auth/request-otp', 'userRequestOtp'); // done
    Route::post('/auth/verify-otp', 'userVerifyOtp'); // done
});

Route::controller(ProductController::class)->group(function() {
    Route::get('/products', 'getProducts'); // done
    Route::get('/products/{productId}', 'getProductDetail')->where('productId', '[0-9]+'); // done
    Route::get('/collections/{collectionId}/products', 'getProductsPerCollection')->where('collectionId', '[0-9]+'); // done
});

Route::controller(TypeController::class)->group(function() {
    Route::post('/products/types/create', 'createType'); // done
    Route::get('/products/types', 'getTypes'); // done
});

Route::controller(OrderController::class)->group(function() {
    Route::get('/orders', 'getOrders'); // done
    Route::get('/orders/{orderId}', 'getOrderDetail')->where('orderId', '[0-9]+'); 
});

Route::get('/collections', [CollectionController::class, 'getCollections']); // done
Route::get('/names', [NameController::class, 'getAllProductCollectionNames']); // done
Route::get('/colors', [ColorController::class, 'getColors']); // done
Route::get('/sizes', [ColorController::class, 'getSizes']); // done
Route::get('/fabrics', [ColorController::class, 'getFabrics']); // done
Route::get('/statuses', [StatusController::class, 'getStatuses']); // done

Route::post('/image/upload', [ImageController::class, 'uploadImage'])->name('image.upload');

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

    Route::middleware(['is_admin'])->group(function() {
        Route::controller(ProductController::class)->group(function() {
            Route::post('/products/create', 'createProduct');
            Route::put('/products/{productId}/update', 'updateProduct')->where('productId', '[0-9]+');
            Route::delete('/products/{productId}/delete', 'deleteProduct')->where('productId', '[0-9]+');
        });

        Route::controller(ProductVariantController::class)->group(function() {
            Route::post('/products/create/{productId}/variant', 'createProductVariant')->where('productId', '[0-9]+');
            Route::patch('/products/update/{productVariantId}/variant', 'updateProductVariant')->where('productVariantId', '[0-9]+');
            Route::delete('/products/delete/{productVariantId}/variant', 'deleteProductVariant')->where('productVariantId', '[0-9]+');
        });

        Route::controller(CollectionController::class)->group(function() {
            Route::post('/collections/create', 'createCollection');
            Route::put('/collections/{collectionId}/update', 'updateCollection')->where('collectionId', '[0-9]+');
            Route::delete('/collections/{collectionId}/delete', 'deleteCollection')->where('collectionId', '[0-9]+');
        }); 
    });
    
});

Route::controller(OrderController::class)->group(function() {
    
});

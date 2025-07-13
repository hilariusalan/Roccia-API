<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\NameController;
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

Route::post('/auth/request-otp', [UserController::class, 'userRequestOtp']);
Route::post('/auth/verify-otp', [UserController::class, 'userVerifyOtp']);

Route::middleware(['auth:api'])->group(function() {
    Route::get('/users', [UserController::class, 'getUserData']);
    Route::put('/users/update', [UserController::class, 'updateUser']);
    Route::delete('/logout', [UserController::class, 'logoutUser']);

    Route::get('/users/addresses', [AddressController::class, 'getUserAddress']);
    Route::post('/users/address/create', [AddressController::class, 'createAddress']);
    Route::put('/users/address/{addressId}/edit', [AddressController::class, 'updateUserAddress'])->where('addressId', '[0-9]+');
    Route::delete('/users/address/{addressId}/delete', [AddressController::class, 'deleteUserAddress'])->where('addressId', '[0-9]+');

    Route::middleware(['is_admin'])->group(function() {
        // add some route here
    });
});

// perlu pembenaran
Route::post('/products', [ProductController::class, 'createProduct']);
Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/names', [NameController::class, 'getAllProductCollectionNames']);
Route::post('/products/types', [TypeController::class, 'createType']);
Route::get('/products/types', [TypeController::class, 'getTypes']);
Route::get('/colors', [ColorResource::class, 'getColors']);
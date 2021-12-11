<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\ImageController;

use App\Models\Motorcycle;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Middleware for authentication
Route::middleware('auth:api')->group(function(){
    Route::get('user', [PassportAuthController::class,'authenticatedUserDetails']);
});

// Register and Login Controllers 
Route::post('register',[PassportAuthController::class,'register']);
Route::post('login',[PassportAuthController::class,'login']);

// Get all products
Route::get('products', [MotorcycleController::class,'index']);

// Users can do crud ops on their products
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('products', [MotorcycleController::class,'store']);
    Route::post('products/{motorcycle}/image', [ImageController::class,'addImage']);
    Route::delete('products/{motorcycle}', [MotorcycleController::class,'soldItem']);
});

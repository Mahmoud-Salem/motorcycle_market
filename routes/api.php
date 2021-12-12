<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VerificationController;

use App\Models\Motorcycle;


// Middleware for authentication
Route::middleware('auth:api')->group(function(){
    Route::get('user', [PassportAuthController::class,'authenticatedUserDetails']);
});

// Verify Email
Route::get('/email/verify/{id}/{hash}',  [VerificationController::class,'__invoke'])->middleware(['signed','throttle:6,1'])->name('verification.verify');

// Register and Login Controllers 
Route::post('register',[PassportAuthController::class,'register']);
Route::post('login',[PassportAuthController::class,'login']);

// Get all products
Route::get('products', [MotorcycleController::class,'index']);

// Users can do crud ops on their products
Route::group(['middleware' => ['auth:api','verified']], function() {
    Route::post('products', [MotorcycleController::class,'store']);
    Route::post('products/{motorcycle}/image', [ImageController::class,'addImage']);
    Route::delete('products/{motorcycle}', [MotorcycleController::class,'soldItem']);
});

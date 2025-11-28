<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PubTypeController;
use App\Http\Controllers\AttributController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CertificationController;

// ADMIN
Route::apiResource('country', CountryController::class);
Route::apiResource('town', TownController::class);
Route::apiResource('district', DistrictController::class);
Route::apiResource('register', UserController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('category', CategoryController::class);
Route::apiResource('pub-type', PubTypeController::class);
Route::apiResource('attribute', AttributController::class);
Route::apiResource('publication', PublicationController::class);
Route::apiResource('payments', PaymentController::class);

Route::post('/users/{user}/toggle-verification', [CertificationController::class, 'toggleVerification']);
Route::post('/certification/revoke/{user}', [CertificationController::class, 'revoke']);
Route::post('/certification/reject/{user}', [CertificationController::class, 'reject']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('country', CountryController::class);
//     Route::apiResource('town', TownController::class);
//     Route::apiResource('district', DistrictController::class);
//     Route::apiResource('category', CategoryController::class);
//     Route::apiResource('pub-type', PubTypeController::class);
//     Route::apiResource('attribute', AttributController::class);
//     Route::apiResource('publication', PublicationController::class);
// });

//USER
// routes/api.php
// Route::get('/me', function () {
//     return response()->json(auth()->user());
// })->middleware('auth:sanctum'); // ou 'auth' selon ta config
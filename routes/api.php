<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\TownController;

// ADMIN
Route::apiResource('country', CountryController::class);
Route::apiResource('town', TownController::class);
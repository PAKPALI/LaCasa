<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PubTypeController;
use App\Http\Controllers\AttributController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PublicationController;

Route::post('/myLogin', [AuthController::class, 'login'])->name('myLogin');
Route::middleware('auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/me/update', [AuthController::class, 'update']);
    Route::post('/me/updateEmail', [AuthController::class, 'updateEmail']);
    Route::post('/me/update-password', [AuthController::class, 'updatePassword']);
    Route::delete('/me/remove-image', [AuthController::class, 'removeProfileImage']);

    Route::get('getMyPublication', [PublicationController::class, 'getMyPublication']);
});

    // Route::middleware(['auth'])->group(function () {

    //     // Pays
    //     Route::resource('country', CountryController::class);

    //     // Villes
    //     Route::resource('town', TownController::class);

    //     // Districts
    //     Route::resource('district', DistrictController::class);

    //     // Catégories
    //     Route::resource('category', CategoryController::class);

    //     // Types de publications
    //     Route::resource('pub-type', PubTypeController::class);

    //     // Attributs
    //     Route::resource('attribute', AttributController::class);

    //     // Publications
    //     Route::resource('publication', PublicationController::class);

    //     // Utilisateurs
    //     Route::resource('users', UserController::class);
    // });
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/{any}', function () {
    return view('index');
    // return view('index2');
})->where('any', '^(?!api).*$');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

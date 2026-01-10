<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\TownController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\CountryController;
// use App\Http\Controllers\PaymentController;
// use App\Http\Controllers\PubTypeController;
// use App\Http\Controllers\AttributController;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CertificationController;

Route::post('/myLogin', [AuthController::class, 'login'])->name('myLogin');

// Routes protégées (auth)
Route::middleware('auth')->group(function () {
    // --- Routes AuthController ---
    Route::controller(AuthController::class)->group(function () {
        Route::get('/me', 'me');
        Route::post('/me/update', 'update');
        Route::post('/me/updateEmail', 'updateEmail');
        Route::post('/me/update-password', 'updatePassword');
        Route::post('/me/update-social', 'updateSocial');
        Route::delete('/me/remove-image', 'removeProfileImage');
    });

    // --- Routes PublicationController ---
    Route::controller(PublicationController::class)->group(function () {
        Route::get('getMyPublication', 'getMyPublication');
        // Route::resource('publication', PublicationController::class);
    });

    // --- Routes CertificationController ---
    Route::controller(CertificationController::class)->group(function () {
        Route::post('/me/certify_payment', 'certifyPayment');
    });

    // Publications
    Route::resource('publication', PublicationController::class);
});

Route::post('/donation/payment', [DonationController::class, 'pay']);

// Routes publiques (tout le monde peut voir les avis)
Route::controller(ReviewController::class)->group(function () {
    Route::get('/reviews', 'index');
    Route::get('/reviews/{review}', 'show');
});

// Routes protégées (auth)
Route::middleware('auth')->controller(ReviewController::class)->group(function () {
    // Poster un avis
    Route::post('/reviews', 'store');

    // Répondre à un avis (admin dans contrôleur ou middleware)
    Route::post('/reviews/{review}/comment', 'comment');

    // Supprimer un avis (admin only)
    Route::delete('/reviews/{review}', 'destroy');

    // Supprimer un commentaire d'un avis (admin only)
    Route::delete('/review-comments/{comment}', 'deleteComment');
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
        // Route API séparée
        Route::get('/api/publications', [PublicationController::class, 'index']);


//     // Utilisateurs
//     Route::resource('users', UserController::class);
// });
Route::get('/categories', [CategoryController::class, 'index']);
// routes/api.php
Route::get('/users/stats', [UserController::class, 'stats']);


Route::get('/{any}', function () {
    return view('index');
    // return view('index2');
})->where('any', '^(?!api).*$');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

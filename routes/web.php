<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Route::get('/{any}', function () {
    return view('index');
    // return view('index2');
})->where('any', '^(?!api).*$');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

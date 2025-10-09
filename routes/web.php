<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/myLogin', [AuthController::class, 'login'])->name('myLogin');
Route::get('/{any}', function () {
    return view('index');
    // return view('index2');
})->where('any', '^(?!api).*$');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

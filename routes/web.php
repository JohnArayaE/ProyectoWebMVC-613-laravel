<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

// HOME
Route::get('/', function () {
    $rides = [];
    return view('index', compact('rides'));
})->name('home');

// LOGIN
Route::get('/login', function () {
    return view('login');
})->name('login');

// TEMPORAL test de POST
Route::post('/test-post', function () {
    return 'POST SI FUNCIONA';
});

// REGISTRATION (VISTA)
Route::get('/registration', [RegistrationController::class, 'create'])
    ->name('registration');

// REGISTRATION (POST)
Route::post('/registration', [RegistrationController::class, 'store'])
    ->name('registration.store');

// ACTIVAR CUENTA
Route::get('/activate-account', [RegistrationController::class, 'activate'])
    ->name('registration.activate');

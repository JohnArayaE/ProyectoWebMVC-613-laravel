<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 👉 ESTA ES TU NUEVA RUTA PARA EL LOGIN
Route::get('/login', function () {
    return view('login');
})->name('login');

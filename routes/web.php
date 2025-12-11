<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RegisterDriverController; 
use App\Http\Controllers\DriverVehicleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminRegistrationController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\RideReservationController;

// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');


// LOGIN (mostrar formulario)
Route::get('/login', [LoginController::class, 'show'])
    ->name('login');

// LOGIN (procesar POST)
Route::post('/login', [LoginController::class, 'login'])
    ->name('login.start');

// LOGOUT
Route::get('/logout', [LoginController::class, 'logout'])
    ->name('logout');

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

// FORMULARIO DE REGISTRO DE CONDUCTOR
Route::get('/register-driver', [RegisterDriverController::class, 'create'])
    ->name('registerDriver.create');

// PROCESAR REGISTRO DE CONDUCTOR
Route::post('/register-driver', [RegisterDriverController::class, 'store'])
    ->name('registerDriver.store');

// VEHICLES (Conductor)
Route::get('/driver/vehicles', [DriverVehicleController::class, 'index'])
    ->name('driver.vehicles');

// Más adelante:
// Route::get('/driver/rides', ...)->name('driver.rides');
// Route::get('/driver/bookings', ...)->name('driver.bookings');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard');

Route::post('/admin/status', [AdminController::class, 'changeStatus'])
    ->name('admin.status'); 

Route::get('/admin/create', [AdminRegistrationController::class, 'create'])
    ->name('admin.register.create');

Route::post('/admin/create', [AdminRegistrationController::class, 'store'])
    ->name('admin.register.store');

Route::get('/configurations', [ConfigurationController::class, 'show'])
    ->name('configurations');

Route::post('/configurations/update', [ConfigurationController::class, 'update'])
    ->name('configurations.update');

Route::get('/bookings', [BookingsController::class, 'index'])
    ->name('bookings')
    ->middleware('auth');

Route::post('/bookings/update', [BookingsController::class, 'updateBooking'])
    ->name('bookings.update')
    ->middleware('auth');

Route::get('/ride/info/{id}', [RideController::class, 'info'])
    ->name('ride.info');
    
Route::post('/ride/reserve', [RideReservationController::class, 'reservar'])
    ->name('ride.reserve');
// ACTIVAR CUENTA
Route::get('/activate-account', [RegistrationController::class, 'activate'])
    ->name('registration.activate');

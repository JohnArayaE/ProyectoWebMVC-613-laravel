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
use App\Http\Controllers\DriverRideController;


// ======================
// HOME
// ======================
Route::get('/', [HomeController::class, 'index'])->name('home');


// ======================
// LOGIN
// ======================
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.start');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


// ======================
// REGISTRO DE USUARIO
// ======================
Route::get('/registration', [RegistrationController::class, 'create'])->name('registration');
Route::post('/registration', [RegistrationController::class, 'store'])->name('registration.store');


// ======================
// REGISTRO DE CHOFER
// ======================
Route::get('/register-driver', [RegisterDriverController::class, 'create'])->name('registerDriver.create');
Route::post('/register-driver', [RegisterDriverController::class, 'store'])->name('registerDriver.store');


// ======================
// DRIVER - VEHICLES
// (ESTAS SON LAS CORRECTAS)
// ======================
// LISTAR vehículos
Route::get('/driver/vehicles', [DriverVehicleController::class, 'index'])
    ->name('driver.vehicles');

// CREAR vehículo (vista)
Route::get('/driver/vehicles/create', [DriverVehicleController::class, 'create'])
    ->name('driver.vehicles.create');

// GUARDAR vehículo
Route::post('/driver/vehicles/store', [DriverVehicleController::class, 'store'])
    ->name('driver.vehicles.store');

// EDITAR vehículo (vista)
Route::get('/driver/vehicles/{id}/edit', [DriverVehicleController::class, 'edit'])
    ->name('driver.vehicles.edit');

// ACTUALIZAR vehículo
Route::put('/driver/vehicles/{id}', [DriverVehicleController::class, 'update'])
    ->name('driver.vehicles.update');

// ELIMINAR vehículo
Route::delete('/driver/vehicles/{id}', [DriverVehicleController::class, 'destroy'])
    ->name('driver.vehicles.destroy');


 // ======================
// DRIVER - RIDES
// ======================

Route::get('/driver/rides', [DriverRideController::class, 'index'])
    ->name('driver.rides');

Route::get('/driver/rides/create', [DriverRideController::class, 'create'])
    ->name('driver.rides.create');

Route::post('/driver/rides/store', [DriverRideController::class, 'store'])
    ->name('driver.rides.store');

Route::get('/driver/rides/{id}/edit', [DriverRideController::class, 'edit'])
    ->name('driver.rides.edit');

Route::put('/driver/rides/{id}', [DriverRideController::class, 'update'])
    ->name('driver.rides.update');

Route::delete('/driver/rides/{id}', [DriverRideController::class, 'destroy'])
    ->name('driver.rides.destroy');

// ======================
// ADMIN
// ======================
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/status', [AdminController::class, 'changeStatus'])->name('admin.status');

Route::get('/admin/create', [AdminRegistrationController::class, 'create'])->name('admin.register.create');
Route::post('/admin/create', [AdminRegistrationController::class, 'store'])->name('admin.register.store');


// ======================
// CONFIGURACIONES
// ======================
Route::get('/configurations', [ConfigurationController::class, 'show'])->name('configurations');
Route::post('/configurations/update', [ConfigurationController::class, 'update'])
    ->name('configurations.update');


// ======================
// BOOKINGS
// ======================
Route::get('/bookings', [BookingsController::class, 'index'])
    ->name('bookings')
    ->middleware('auth');

Route::post('/bookings/update', [BookingsController::class, 'updateBooking'])
    ->name('bookings.update')
    ->middleware('auth');


// ======================
// RIDES - pasajeros 
// ======================
Route::get('/ride/info/{id}', [RideController::class, 'info'])->name('ride.info');

Route::post('/ride/reserve', [RideReservationController::class, 'reservar'])
    ->name('ride.reserve');


// ======================
// ACTIVACIÓN DE CUENTA
// ======================
Route::get('/activate-account', [RegistrationController::class, 'activate'])
    ->name('registration.activate');

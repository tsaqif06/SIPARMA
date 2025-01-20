<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DestinationController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/alam', [HomeController::class, 'wisataAlam'])->name('home.alam');
Route::get('/wahana', [HomeController::class, 'wisataWahana'])->name('home.wahana');
Route::get('/restoran', [HomeController::class, 'restoran'])->name('home.restoran');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/restaurants/{slug}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/complain', function () {
    return view('complain.index');
});

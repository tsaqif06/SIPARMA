<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\RestaurantController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/alam', [HomeController::class, 'wisataAlam'])->name('home.alam');
Route::get('/wahana', [HomeController::class, 'wisataWahana'])->name('home.wahana');
Route::get('/restoran', [HomeController::class, 'restoran'])->name('home.restoran');


Route::get('/destinations/{id}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/complain', function () {
    return view('complain.index');
});

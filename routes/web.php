<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminPlaceController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminDestinationController;

// Route::get('/', [HomeController::class, 'index'])->name('home.index');
// Route::get('/wisata', [HomeController::class, 'wisata'])->name('home.wisata');
// Route::get('/tempat', [HomeController::class, 'tempat'])->name('home.tempat');

// Route::prefix('home')->middleware('auth')->controller(HomeController::class)->group(function () {
//     Route::get('/', 'index')->name('home.index');
//     Route::get('/wisata', 'wisata')->name('home.wisata');
//     Route::get('/tempat', 'tempat')->name('home.tempat');
// });

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::name('home.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/wisata', [HomeController::class, 'wisata'])->name('wisata');
    Route::get('/tempat', [HomeController::class, 'tempat'])->name('tempat');
});

Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destination.show');

Route::get('/places/{slug}', [PlaceController::class, 'show'])->name('place.show');

Route::get('/complain', function () {
    return view('complain.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginFormAdmin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAdmin'])->name('login.post');

    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/', [HomeController::class, 'indexAdmin'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'delete'])->name('user.delete');

        Route::resource('users', AdminUserController::class);
        Route::resource('destinations', AdminDestinationController::class);
        Route::resource('places', AdminPlaceController::class);
    });
});

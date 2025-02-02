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
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminRideController;

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

        Route::resource('users', AdminUserController::class);

        Route::get('destinations/{destination}/facilities', [AdminDestinationController::class, 'facilities'])->name('destinations.facilities');
        Route::get('destinations/{destination}/rides', [AdminDestinationController::class, 'rides'])->name('destinations.rides');
        Route::resource('destinations', AdminDestinationController::class);

        Route::prefix('gallery')->group(function () {
            Route::get('{type}', [AdminGalleryController::class, 'index'])->name('gallery.index');
            Route::get('{type}/create', [AdminGalleryController::class, 'create'])->name('gallery.create');

            Route::post('{type}', [AdminGalleryController::class, 'store'])->name('gallery.store');
            Route::delete('{type}/{gallery}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');
        });

        Route::prefix('facility')->group(function () {
            Route::get('{type}', [AdminFacilityController::class, 'index'])->name('facility.index');
            Route::get('{type}/create', [AdminFacilityController::class, 'create'])->name('facility.create');

            Route::post('{type}', [AdminFacilityController::class, 'store'])->name('facility.store');
            Route::delete('{type}/{facility}', [AdminFacilityController::class, 'destroy'])->name('facility.destroy');
        });

        Route::resource('rides', AdminRideController::class);

        Route::get('/places/manage', [AdminPlaceController::class, 'manage'])->name('places.manage');
        Route::resource('places', AdminPlaceController::class);
    });
});

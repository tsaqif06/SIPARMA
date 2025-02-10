<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminRideController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminPlaceController;
use App\Http\Controllers\AdminPromoController;
use App\Http\Controllers\AdminBundleController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminBundleItemController;
use App\Http\Controllers\AdminDestinationController;

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

Route::middleware(['auth', 'is_user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/destinations/checkout/{slug}', [DestinationController::class, 'checkout'])->name('destination.checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/payment/{transaction}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{transaction}/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/invoice/{order_id}', [PaymentController::class, 'invoice'])->name('payment.invoice');

    Route::get('/complain', function () {
        return view('complain.index');
    });
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

        Route::get('places/approval', [AdminPlaceController::class, 'approval'])->name('places.approval');
        Route::post('places/{adminplace}/update-status', [AdminPlaceController::class, 'updateStatus'])->name('places.updateStatus');

        Route::resource('places', AdminPlaceController::class);

        Route::prefix('gallery')->group(function () {
            Route::get('{type}', [AdminGalleryController::class, 'index'])->name('gallery.index');
            Route::get('{type}/create', [AdminGalleryController::class, 'create'])->name('gallery.create');

            Route::post('{type}', [AdminGalleryController::class, 'store'])->name('gallery.store');
            Route::delete('{type}/{gallery}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');
        });

        Route::prefix('facility')->group(function () {
            Route::get('{type}', [AdminFacilityController::class, 'index'])->name('facility.index');
            Route::get('{type}/create', [AdminFacilityController::class, 'create'])->name('facility.create');
            Route::get('{type}/{facility}/edit', [AdminFacilityController::class, 'edit'])->name('facility.edit');

            Route::post('{type}', [AdminFacilityController::class, 'store'])->name('facility.store');
            Route::put('{type}/{facility}', [AdminFacilityController::class, 'update'])->name('facility.update');
            Route::delete('{type}/{facility}', [AdminFacilityController::class, 'destroy'])->name('facility.destroy');
        });

        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');

        Route::resource('promo', AdminPromoController::class);

        // Admin Wisata
        Route::resource('rides', AdminRideController::class);
        Route::prefix('bundle')->group(function () {
            Route::get('', [AdminBundleController::class, 'index'])->name('bundle.index');
            Route::get('create', [AdminBundleController::class, 'create'])->name('bundle.create');
            Route::post('', [AdminBundleController::class, 'store'])->name('bundle.store');
            Route::get('{bundle}/edit', [AdminBundleController::class, 'edit'])->name('bundle.edit');
            Route::put('{bundle}', [AdminBundleController::class, 'update'])->name('bundle.update');
            Route::delete('{bundle}', [AdminBundleController::class, 'destroy'])->name('bundle.destroy');

            Route::prefix('items/{bundle}')->group(function () {
                Route::get('', [AdminBundleItemController::class, 'index'])->name('bundle.items.index');
                Route::get('getrides', [AdminBundleItemController::class, 'getRides'])->name('bundle.items.getrides');
                Route::get('create', [AdminBundleItemController::class, 'create'])->name('bundle.items.create');
                Route::post('', [AdminBundleItemController::class, 'store'])->name('bundle.items.store');
                Route::get('{item}/edit', [AdminBundleItemController::class, 'edit'])->name('bundle.items.edit');
                Route::put('{item}', [AdminBundleItemController::class, 'update'])->name('bundle.items.update');
                Route::delete('{item}', [AdminBundleItemController::class, 'destroy'])->name('bundle.items.destroy');
            });
        });
        // End Admin Wisata
    });
});

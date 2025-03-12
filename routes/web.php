<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminRideController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AdminPlaceController;
use App\Http\Controllers\AdminPromoController;
use App\Http\Controllers\AdminBundleController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminBalanceController;
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminBundleItemController;
use App\Http\Controllers\AdminWithdrawalController;
use App\Http\Controllers\AdminDestinationController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminArticleController;

// user auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('no_admin')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::post('/recommendation/submit', [HomeController::class, 'submitRecommendation'])->name('home.recommendation.store');
    Route::get('/destinations', [DestinationController::class, 'browse'])->name('destination.browse');
    Route::get('/places', [PlaceController::class, 'browse'])->name('place.browse');
    Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destination.show');
    Route::get('/places/{slug}', [PlaceController::class, 'show'])->name('place.show');
});


Route::middleware(['auth', 'is_user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/transactions/history', [ProfileController::class, 'transactionHistory'])->name('transactions.history');
    Route::get('/adminplace/verification', [ProfileController::class, 'adminPlaceVerification'])->name('admin.verification');
    Route::post('/adminplace/verification', [ProfileController::class, 'storeVerification'])->name('admin.verification.store');

    Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

    Route::get('/destinations/checkout/{slug}/{type?}', [DestinationController::class, 'checkout'])->name('destination.checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/payment/{transaction}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{transaction}/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/invoice/{order_id}', [PaymentController::class, 'invoice'])->name('payment.invoice');
    Route::get('/invoice/download/{order_id}', [PaymentController::class, 'downloadInvoice'])->name('payment.invoice.download');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginFormAdmin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAdmin'])->name('login.post');

    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/', [HomeController::class, 'indexAdmin'])->name('dashboard');

        Route::resource('users', AdminUserController::class);

        Route::get('destinations/recommendation', [AdminDestinationController::class, 'recommendation'])->name('destinations.recommendation');
        Route::get('destinations/recommendation/{id}', [AdminDestinationController::class, 'showRecommendation'])->name('recommendations.show');
        Route::get('destinations/recommendation/change/{id}', [AdminDestinationController::class, 'changeStatus'])->name('recommendations.changeStatus');
        Route::put('destinations/recommendation/update/{id}', [AdminDestinationController::class, 'updateStatus'])->name('recommendations.updateStatus');

        Route::get('destinations/{destination}/facilities', [AdminDestinationController::class, 'facilities'])->name('destinations.facilities');
        Route::get('destinations/{destination}/rides', [AdminDestinationController::class, 'rides'])->name('destinations.rides');
        Route::resource('destinations', AdminDestinationController::class);

        Route::get('places/approval', [AdminPlaceController::class, 'approval'])->name('places.approval');
        Route::post('places/{adminplace}/update-status', [AdminPlaceController::class, 'updateStatus'])->name('places.updateStatus');
        Route::resource('places', AdminPlaceController::class);

        Route::resource('articles', AdminArticleController::class);

        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');
        Route::get('/complaints/{id}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
        Route::put('/complaints/{id}', [ComplaintController::class, 'update'])->name('complaints.update');

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

        Route::get('transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{code}', [AdminTransactionController::class, 'show'])->name('transactions.show');

        Route::get('balance', [AdminBalanceController::class, 'index'])->name('balance.index');
        Route::get('balanceadmin', [AdminBalanceController::class, 'indexAdmin'])->name('balance.indexAdmin');
        Route::get('balance/recap', [AdminBalanceController::class, 'monthlyRecapIndex'])->name('balance.recap');
        Route::get('balanceadmin/recap', [AdminBalanceController::class, 'monthlyRecapIndexAdmin'])->name('balance.recapAdmin');
        Route::get('balance/{id}', [AdminBalanceController::class, 'show'])->name('balance.show');

        Route::get('withdrawal/approval', [AdminWithdrawalController::class, 'approval'])->name('withdrawal.approval');
        Route::get('withdrawal/{withdrawal}/approve-form', [AdminWithdrawalController::class, 'approveForm'])->name('withdrawal.approveForm');
        Route::post('withdrawal/{withdrawal}/update-status', [AdminWithdrawalController::class, 'updateStatus'])->name('withdrawal.updateStatus');
        Route::get('withdrawal', [AdminWithdrawalController::class, 'index'])->name('withdrawal.index');
        Route::get('withdrawal/history', [AdminWithdrawalController::class, 'history'])->name('withdrawal.history');
        Route::get('withdrawal/request', [AdminWithdrawalController::class, 'create'])->name('withdrawal.create');
        Route::post('withdrawal/request', [AdminWithdrawalController::class, 'store'])->name('withdrawal.store');
        Route::get('withdrawal/{withdrawal}/edit', [AdminWithdrawalController::class, 'edit'])->name('withdrawal.edit');
        Route::put('withdrawal/{withdrawal}', [AdminWithdrawalController::class, 'update'])->name('withdrawal.update');
        Route::delete('withdrawal/{withdrawal}', [AdminWithdrawalController::class, 'destroy'])->name('withdrawal.destroy');

        // Admin Wisata
        Route::resource('promo', AdminPromoController::class);
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

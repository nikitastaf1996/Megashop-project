<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',function(){
    return redirect('/catalog');
});

Route::prefix('user')->middleware(['auth', 'verified'])->group(
    function () {
        Route::get('/', function () {
            return redirect('/user/orders');
        })->name('dashboard');
        Route::match(['GET', 'POST'], '/orders', [DashboardController::class, 'indexOrders']);
        Route::match(['GET', 'POST'], '/settings/{page?}', [DashboardController::class, 'indexSettings']);
        Route::match(['GET', 'POST'], '/settings/update/{page?}', [DashboardController::class, 'updateSettings']);

    }
);

require __DIR__ . '/auth.php';


Route::match(['GET', 'POST'], '/catalog/{category?}', [ItemController::class, 'index'])->name('catalog');
Route::match(['GET', 'POST'], '/product/{item}', [ItemController::class, 'show']);
Route::match(['GET', 'POST'], '/product/{item}/reviews', [ReviewController::class, 'index']);
Route::post('/search', [ItemController::class, 'search']);
Route::post('/favorite', [FavoriteController::class, 'index']);
Route::match(['GET', 'POST'], '/favorite', [FavoriteController::class, 'index']);
Route::prefix('cart')->group(
    function () {
        Route::match(['GET', 'POST'], '/', [CartController::class, 'index']);
        Route::post('addToCart', [CartController::class, 'addToCart']);
        Route::post('removeFromCart', [CartController::class, 'removeFromCart']);
        Route::post('updateFavorite', [FavoriteController::class, 'updateFavorite']);
        Route::get('removeTemp', [CartController::class, 'removeTemp']);
        Route::get('mergeTemp', [CartController::class, 'mergeTemp']);
    }
);
Route::prefix('order')->middleware(['auth', 'verified'])->group(
    function () {
        Route::match(['GET', 'POST'], '/checkOrder', [OrderController::class, 'checkOrder']);
        Route::match(['GET', 'POST'], '/confirmOrder', [OrderController::class, 'confirmOrder']);
    }
);
Route::prefix('payment')->middleware(['auth', 'verified'])->group(
    function () {
        Route::match(['GET', 'POST'], '/{item}', [PaymentController::class, 'createPaymentLinkForOneItem'])->name('payment');
        Route::match(['GET', 'POST'], '/all', [PaymentController::class, 'createPaymentLinkForAllItems']);
        Route::match(['GET', 'POST'], '/uuid/{payment_uuid}&{price}', [PaymentController::class, 'simulatePayment'])->name('paymentSimulation');
    }
);


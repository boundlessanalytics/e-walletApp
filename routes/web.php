<?php

use App\Http\Controllers\CallbackController;
use App\Http\Controllers\FundWalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/paystack_pay', [FundWalletController::class, 'initializePaystackPayment'])->name('paystack_pay');

Route::post('/processPaystackPayment', [FundWalletController::class, 'processPaystackPayment'])->name('processPaystackPayment');

Route::get('/stripe_pay', [FundWalletController::class, 'initializeStripePayment'])->name('stripe_pay');

Route::post('/process_payment', [FundWalletController::class, 'processStripePayment'])->name('process_payment');

Route::get('/payment/callback', [CallbackController::class, 'paystack']);
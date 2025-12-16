<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransactionController;


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

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name("home");
Route::get('/product/{id}', [HomeController::class, 'show'])
    ->name('product');

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product');


Route::post('/checkout', [CheckoutController::class, 'process'])->name("checkout-process");


Route::get('/checkout/{transaction}', [CheckoutController::class, 'checkout'])
     ->name('checkout');

Route::get('/transactions', [TransactionController::class, 'index'])->name("transactions");

Route::get('/checkout/success/{transaction}', [CheckoutController::class, 'success']) ->name("checkout.success");

Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
    ->name('transactions.destroy');
Route::post('/login', [AuthController::class, 'login']);

Auth::routes();

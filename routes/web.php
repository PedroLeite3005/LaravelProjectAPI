<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BuyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SellController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/comprar', [\App\Http\Controllers\ApiController::class, 'stocksIndex'])->name('comprar');
    Route::get('/stocks/buy/{stock}', [ApiController::class, 'buyStockForm'])->name('stocks.buy_form');
    Route::get('/stocks/{page?}/{searchTerm?}', [ApiController::class, 'stocksIndex'])->name('stocks.index');

    Route::post('/stocks', [BuyController::class, 'moneyValidate'])->name('buyReceive');
    
    Route::get('/vender/{page?}/{searchTerm?}',[SellController::class, 'sellIndex'])->name('vender');
    Route::post('/vender',[SellController::class, 'actionSold'])->name('sold');

    Route::get('/usuario', [\App\Http\Controllers\USerController::class, 'index'])->name('users.index');

    Route::get('/transaction', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transaction');
    Route::post('/transaction',  [\App\Http\Controllers\TransactionController::class, 'deposit']);

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

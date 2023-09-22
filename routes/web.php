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
    
    Route::prefix('/stock')->group(function() {
        Route::get('/index/{page?}/{searchTerm?}', [ApiController::class, 'stocksIndex'])->name('stocks.index');
        Route::get('/buy/{stock}', [ApiController::class, 'buyStockForm'])->name('stocks.buyForm');
        Route::post('/buy', [BuyController::class, 'buyStock'])->name('stocks.buy');
        Route::get('/sell/{page?}/{searchTerm?}',[SellController::class, 'sellIndex'])->name('stocks.sellList');
        Route::post('/sell',[SellController::class, 'sellStock'])->name('stocks.sell');
    }); 

    Route::get('/historic/{page?}', [\App\Http\Controllers\USerController::class, 'index'])->name('users.historic');

    Route::get('/transaction', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transaction');
    Route::post('/transaction/deposit',  [\App\Http\Controllers\TransactionController::class, 'deposit'])->name('transaction.deposit');
    Route::post('/transaction/withdraw',  [\App\Http\Controllers\TransactionController::class, 'withdraw'])->name('transaction.withdraw');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

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
        Route::get('/sell',[SellController::class, 'sellIndex'])->name('stocks.sellList');
    }); 

    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction');

    Route::get('/historic/{page?}/{type?}/{searchTerm?}', [UserController::class, 'index'])->name('users.historic');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::prefix('/mercado-livre')->group(function() {
        Route::view('/', 'MercadoLivre.index')->name('mercado-livre.index');
        Route::view('/postSalesChat/{orderId}/{sellerId}', 'MercadoLivre.postSalesChat')->name('mercado-livre.postSalesChat');
    });
});

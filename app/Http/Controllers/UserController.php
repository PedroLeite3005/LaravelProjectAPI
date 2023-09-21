<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = User::with('userStock', 'transactionHistory')
                    ->where('id', Auth::user()->id)
                    ->first();

        $userStocks = $user->userStock;

        $page = $request->page ?? 1;
        $stocksPerPage = 10;
        $userStocks = collect($userStocks);

        $lastPage = ceil($userStocks->count() / $stocksPerPage);
        $skip = ($page - 1) * $stocksPerPage;
        $userStocks = $userStocks->slice($skip, $stocksPerPage);
        
        $transactions = $user->transactionHistory->sortByDesc('created_at');
        
        return view('users.index', [
            'page' => $page,
            'lastPage' => $lastPage,
            'userStocks' => $userStocks,
            'transactions' => $transactions
        ]); 
    }
}

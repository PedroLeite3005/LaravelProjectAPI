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

        $transactions = $user->transactionHistory;

        $page = $request->page ?? 1;
        $stocksPerPage = 10;
        $transactions = collect($transactions);

        $transactions = $user->transactionHistory->sortByDesc('created_at');

        $lastPage = ceil($transactions->count() / $stocksPerPage);
        $skip = ($page - 1) * $stocksPerPage;
        $transactions = $transactions->slice($skip, $stocksPerPage);
        
        return view('users.historic', [
            'page' => $page,
            'lastPage' => $lastPage,
            'transactions' => $transactions
        ]); 
    }
}

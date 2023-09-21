<?php

namespace App\Http\Controllers;

use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userStocks = $user->userStock;

        $page = $request->page ?? 1;
        $stocksPerPage = 10;
        $userStocks = collect($userStocks);

        $lastPage = ceil($userStocks->count() / $stocksPerPage);
        $skip = ($page - 1) * $stocksPerPage;
        $userStocks = $userStocks->slice($skip, $stocksPerPage);
        
        $transactions = TransactionHistory::where('user_id', $user->id)->orderBy('created_at','desc')->get();

        return view('users.index', [
            'page' => $page,
            'lastPage' => $lastPage,
            'userStocks' => $userStocks
        ],compact('transactions'));
    }

}

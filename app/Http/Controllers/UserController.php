<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
public function index(Request $request)
{
    /** @var User $user */
    $user = User::with('userStock', 'transactionHistory')
                ->where('id', Auth::user()->id)
                ->first();

    $searchTerm = $request->searchTerm;
    $transactions = $user->transactionHistory;
    $category = $request->input('type');

    if ($searchTerm) {
        $transactions = $transactions->filter(function ($s) use ($searchTerm) {
            return Str::contains(strtoupper($s->name), strtoupper($searchTerm));
        });
    }

    if ($category) {
        $transactions = $transactions->filter(function ($s) use ($category) {
            return Str::contains(strtoupper($s->type), strtoupper($category));
        });
    }

    $page = $request->page ?? 1;
    $stocksPerPage = 10;
    $transactions = collect($transactions);

    $transactions = $transactions->sortByDesc('created_at');

    $lastPage = ceil($transactions->count() / $stocksPerPage);
    $skip = ($page - 1) * $stocksPerPage;
    $transactions = $transactions->slice($skip, $stocksPerPage);
    
    return view('users.historic', [
        'page' => $page,
        'lastPage' => $lastPage,
        'transactions' => $transactions,
        'searchTerm' => $searchTerm,
    ]); 
    }
}

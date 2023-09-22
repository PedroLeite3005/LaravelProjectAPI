<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellController extends Controller
{
    public function sellIndex(Request $request)
    {
        $user = Auth::user();
        $userStocks = $user->userStock;

        $searchTerm = $request->searchTerm;
        
        if ($request->searchTerm) {
            $userStocks = $userStocks->filter(function ($s) use ($searchTerm) {
                return Str::contains(strtoupper($s->stock_name), strtoupper($searchTerm));
            });
        }

        $page = $request->page ?? 1;
        $stocksPerPage = 10;
        $userStocks = collect($userStocks);

        $lastPage = ceil($userStocks->count() / $stocksPerPage);
        $skip = ($page - 1) * $stocksPerPage;
        $userStocks = $userStocks->slice($skip, $stocksPerPage);

        return view('users.sellStock', [
            'page' => $page,
            'lastPage' => $lastPage,
            'userStocks' => $userStocks,
            'searchTerm' => $request->searchTerm
        ]);
    }

public function sellStock(Request $request){
    /** @var User $user */
    $user = Auth::user();

    $userStock = UserStock::find($request->stock_id);

    if (!$userStock) {
        return back()->with('error', 'Ação não encontrada');
    }

    try {
        $result = DB::transaction(function() use ($user, $request, $userStock) {
            $userStock->decrement('stock_quantity', $request->quantity);

            if ($userStock->stock_quantity == 0) {
                $userStock->delete(); 
            }

            $user->increment('money', $request->sellAmount);

            $user->transactionHistory()->create([
                'type' => 'venda',
                'name' => $request->stock_name, 
                'quantity' => $request->quantity,
                'price' => $request->sellAmount,
            ]);
            return true;
        });
    
    } catch (\Exception $e) {
        return false;
        }

    return $result
        ? back()->with('success', 'Venda registrada com sucesso')
        : back()->with('error', 'Houve algum erro ao registrar a venda');
    }
}

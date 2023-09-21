<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStock;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function sellIndex(Request $request)
    {
        $user = Auth::user();
        $userStocks = $user->userStock;

        $page = $request->page ?? 1;
        $stocksPerPage = 10;
        $userStocks = collect($userStocks);

        $lastPage = ceil($userStocks->count() / $stocksPerPage);
        $skip = ($page - 1) * $stocksPerPage;
        $userStocks = $userStocks->slice($skip, $stocksPerPage);

        $searchTerm = $request->searchTerm;

        if ($request->searchTerm) {
            $userStocks = $userStocks->filter(function ($s) use ($searchTerm) {
                stristr($s->stock_name, $searchTerm) !== false || stristr($s->stock_price, $searchTerm) !== false;
            });
        }
        

        return view('users.vender', [
            'page' => $page,
            'lastPage' => $lastPage,
            'userStocks' => $userStocks,
            'searchTerm' => $request->searchTerm
        ]);
    }

    public function actionSold(Request $request){
        /** @var User $user */
        $user = Auth::user();

        $sellAmount = $request->sellAmount;
        $userMoney = $user->money;
        $name = $request->stock_name;
        
        $userStock = UserStock::find($request->stock_id);

        if (!$userStock) {
            return redirect('/home');
        }
        $quantity = $request->quantity;
        $userStock->stock_quantity -= $request->quantity;
        $userStock->save();

        if ($userStock->stock_quantity == 0) {
            $userStock->delete(); 
        }

        $newValue = $userMoney + $sellAmount;
        $user->money = $newValue;
        $user->save();

        $transaction = new TransactionHistory([
            'user_id' => Auth::user()->id, 
            'type' => 'venda', 
            'name' => $name,
            'quantity' => $quantity, 
            'price' => $sellAmount, 
        ]);
        $transaction->save();

        return back();
    }
}

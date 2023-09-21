<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserStock;
use App\Models\TransactionHistory;


class BuyController extends Controller
{   
    public function moneyValidate(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $totalAmount = $request->totalAmount;

        if ($totalAmount) {
            $userMoney = $user->money; 
            if($userMoney < $totalAmount){
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo insuficiente'
                ]);
            } else {
                $newValue = $userMoney - $totalAmount;
                $user->money = $newValue;
                $user->save();

                $name = $request->stock_name;
                $stockPrice = $request->stock_price;
                $quantity = $request->input('quantity');

                $userStock = new UserStock([
                    'user_id' => Auth::user()->id,
                    'stock_name' => $name, 
                    'stock_price' => $stockPrice, 
                    'stock_quantity' => $quantity, 
                ]);
                $userStock->save();

                $transaction = new TransactionHistory([
                    'user_id' => Auth::user()->id,
                    'type' => 'compra',
                    'name' => $name,
                    'quantity' => $quantity, 
                    'price' => $totalAmount,
                ]);
                $transaction->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Débito registrado na conta'
                ]);
            }
        }else {
            return back();
        }
    }

}

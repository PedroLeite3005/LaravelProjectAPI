<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class BuyController extends Controller
{   
    public function buyStock(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        try {            
            if (empty($request->totalAmount) || $user->money < $request->totalAmount) {
                throw new Exception('Saldo Insuficiente');
            }

            DB::transaction(function() use ($user, $request) {
                $user->update([
                    'money' => $user->money - $request->totalAmount
                ]);

                $user->userStock()->create([
                    'stock_name' => $request->stock_name, 
                    'stock_price' => $request->stock_price, 
                    'stock_quantity' => $request->quantity, 
                ]);

                $user->transactionHistory()->create([
                    'type' => 'compra',
                    'name' => $request->stock_name,
                    'quantity' => $request->quantity, 
                    'price' => $request->totalAmount,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Débito registrado na conta'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

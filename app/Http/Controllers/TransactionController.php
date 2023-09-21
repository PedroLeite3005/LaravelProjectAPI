<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        return view('users.transaction');
    }

    public function deposit(Request $request)
    {  
        
        $request->validate([
            'valor' => 'numeric',
        ]);
        /** @var User $user */
        $user = Auth::user(); 
        if ($request->input('deposit')) {
            $valueNow = $user->money;
            $newValue = $valueNow + $request->input('deposit');
            $user->money = $newValue;
            $user->save();
            
            return back();

        }elseif($request->input('withdraw')) {
            $withdrawAmount = $request->input('withdraw');
            $userMoney = $user->money;

            if ($withdrawAmount > $userMoney) {
                return back();;
            }

            $newValue = $userMoney - $withdrawAmount;
            $newValue = round($newValue, 2);

            $user->money = $newValue;
            $user->save();
            return back();
        }
    }
}

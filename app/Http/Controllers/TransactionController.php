<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        return view('users.transaction');
    }

    public function deposit(DepositRequest $request)
    {  
        $request = $request->validated();

        /** @var User $user */
        $user = Auth::user(); 

        $result = $user->update([
            'money' => $user->money + $request['deposit']
        ]);
        
        return $result
                ? back()->with('status', 'Depósito registrado com sucesso')
                : back()->with('error', 'Houve algum erro ao registrar o depósito');
    }

    public function withdraw(WithdrawRequest $request)
    {
        $request = $request->validated();
        /** @var User $user */
        $user = auth()->user();

        $result = $user->update([
            'money' => $user->money - $request['withdraw']
        ]);

        return $result 
                ? back()->with('status', 'Saque realizado com sucesso') 
                : back()->with('error', 'Houve algum erro ao realizar o saque');
    }
}

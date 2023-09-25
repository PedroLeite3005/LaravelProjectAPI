<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Models\User;
use App\http\Requests\WithdrawRequest;
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

        $withdrawAmount = $request['withdraw'];

        if ($withdrawAmount > $user->money) {
            return back()->with('error', 'Saldo Insuficiente');
        }

        $newMoney = round($user->money - $withdrawAmount, 2);
        $result = $user->update([
            'money' => $newMoney
        ]);

        return $result
                ? back()->with('status', 'Operação realizada com sucesso')
                : back()->with('error', 'Houve algum erro ao registrar a operação');
    }
}

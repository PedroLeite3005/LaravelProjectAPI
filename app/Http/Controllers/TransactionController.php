<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Models\User;
use App\http\Requests\WithdrawRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $response = DB::transaction(function () use ($user, $request) {
            $user->update([
                'money' => $user->money + $request['deposit']
            ]);

            $user->transactionHistory()->create([
                'type' => 'deposito',
                'name' => 'Depósito',
                'quantity' => '-',
                'price' => $request['deposit']
            ]);
            return true;
        });
         return $response
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

        $response = DB::transaction(function () use ($user, $withdrawAmount) {
            $newMoney = round($user->money - $withdrawAmount, 2);
            $user->update([
                'money' => $newMoney
            ]);

            $user->transactionHistory()->create([
                'type' => 'saque',
                'name' => 'Saque',
                'quantity' => '-',
                'price' => $withdrawAmount
            ]);
            return true;
        });
        return $response
                ? back()->with('status', 'Operação realizada com sucesso')
                : back()->with('error', 'Houve algum erro ao registrar a operação');
    }
}

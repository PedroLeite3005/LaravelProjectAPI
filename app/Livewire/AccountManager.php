<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AccountManager extends Component
{
    protected User $user;
    
    public float $money;
    public $depositValue;
    public $withdrawValue;

    protected $rules = [
      'depositValue' => 'numeric|min:0.01',
      'withdrawValue' => 'numeric|min:0.01'
    ];

    protected $messages = [
        'depositValue.numeric' => 'O valor de depósito deve ser numérico.',
        'depositValue.min' => 'O valor de depósito deve ser no minimo um centavo.',
        'withdrawValue.numeric' => 'O valor de saque deve ser numérico.',
        'withdrawValue.min' => 'O valor de saque deve ser no minimo um centavo.'
    ];
 
    public function mount()
    {
        $this->money = auth()->user()->money;
    }

    public function deposit()
    {
        $this->validateOnly('depositValue');
        
        try {
            $response = DB::transaction(function () {
                /** @var User $user */
                $user = auth()->user();
                $user->update([
                    'money' => $user->money + $this->depositValue
                ]);
    
                $user->transactionHistory()->create([
                    'type' => 'deposito',
                    'name' => 'Depósito',
                    'quantity' => '-',
                    'price' => $this->depositValue
                ]);
                
                $this->money = $user->money;
                
                return true;
            });
        } catch (\Exception $e) {
            return false;
        }
        
       $this->reset();
        return $response
        ? back()->with('status', 'Depósito registrado com sucesso')
        : back()->with('error', 'Houve algum erro ao registrar o depósito');
    }

    public function withdraw()
    {
        $this->validateOnly('withdrawValue');
        try {
            /** @var User $user */
            $user = auth()->user();
            $withdrawAmount = $this->withdrawValue;

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

            $this->money = $user->money;
            $this->withdrawValue = 0;
            
            return true;
        });
    
        } catch (\Exception $e) {
            return false;
        }   

        return $response
        ? back()->with('status', 'Saque registrado com sucesso')
        : back()->with('error', 'Houve algum erro ao registrar o saque');
    }


    public function render()
    {
        return view('livewire.account-manager');
    }
}

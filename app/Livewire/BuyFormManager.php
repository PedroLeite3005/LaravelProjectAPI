<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Exception;

class BuyFormManager extends Component
{

    public float $money;
    public $stocks;
    public $stock;

    public function mount()
    {
        $this->money = auth()->user()->money;
    }

    public function index(string $stock_name, float $stock_price, int $quantity, float $totalAmount)
    {
         /** @var User $user */
         $user = auth()->user();

         try {            
             if (empty($totalAmount) || $user->money < $totalAmount) {
                 throw new Exception('Saldo Insuficiente');
             }
 
             DB::transaction(function() use ($user, $stock_name, $stock_price, $quantity, $totalAmount) {
                 $user->update([
                     'money' => $user->money - $totalAmount
                 ]);
 
                 $user->userStock()->create([
                     'stock_name' => $stock_name, 
                     'stock_price' => $stock_price, 
                     'stock_quantity' => $quantity, 
                 ]);
 
                 $user->transactionHistory()->create([
                     'type' => 'compra',
                     'name' => $stock_name,
                     'quantity' => $quantity, 
                     'price' => $totalAmount,
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

    public function render()
    {
        return view('livewire.buy-form-manager', [
            'stock' => $this->stocks
        ]);
    }
}

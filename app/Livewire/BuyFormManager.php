<?php

namespace App\Livewire;

use GuzzleHttp\Client;  
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Exception;

class BuyFormManager extends Component
{

    public float $money;
    public $stock;
    public float $totalAmount;
    public float $stock_price = 0.0;
    public string $stock_name;
    public int $quantity = 1;

    public function mount(string $stock = 'PETR4')
    {
        $this->money = auth()->user()->money;
        $this->stock_name = $stock;
        $this->totalAmount = $this->quantity * $this->stock_price;

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
        ];

        $response = $client->get('https://brapi.dev/api/quote/' . $stock . '?token=86wrdergHbq1wsQc8BrWNF', [
            'headers' => $headers,
        ]);

        $this->stock = json_decode($response->getBody())->results[0];
    }

    public function index()
    {
         /** @var User $user */
         $user = auth()->user();

         try {            
             if (empty($this->totalAmount) || $user->money < $this->totalAmount) {
                 throw new Exception('Saldo Insuficiente');
             }
 
             DB::transaction(function() use ($user) {
                 $user->update([
                     'money' => $user->money - $this->totalAmount
                 ]);
 
                 $user->userStock()->create([
                     'stock_name' => $this->stock_name, 
                     'stock_price' => $this->stock_price, 
                     'stock_quantity' => $this->quantity, 
                 ]);
 
                 $user->transactionHistory()->create([
                     'type' => 'compra',
                     'name' => $this->stock_name,
                     'quantity' => $this->quantity, 
                     'price' => $this->totalAmount,
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
            'stock' => $this->stock,
        ]);	
    }
}

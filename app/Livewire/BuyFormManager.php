<?php

namespace App\Livewire;

use GuzzleHttp\Client; 
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Exception;

class BuyFormManager extends Component
{
    public $totalAmount;
    public $stock_name;
    public $stock_price;
    public $quantity;
    public float $money;
    public $stocks;
    public $stock;

    public function mount()
    {
        $this->money = auth()->user()->money;
        $this->stock = request('stock');
    }

    public function list()
    {
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
        ];

        $response = $client->get('https://brapi.dev/api/quote/' . $this->stock . '?token=86wrdergHbq1wsQc8BrWNF', [
            'headers' => $headers,
        ]);

        $stockInfo = json_decode($response->getBody())->results[0];
      
        $this->stocks = $stockInfo;
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
            'stock' => $this->stocks
        ]);
    }
}

<?php

namespace App\Livewire;

use GuzzleHttp\Client;  
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Exception;

class BuyFormManager extends Component
{
    public string $stock_name;
    public float $stock_price = 0.0;
    public $stock;
    public float $totalAmount;
    public int $quantity = 1;
    public $isLoading = false;

    protected float $money;

    public function mount(string $stock)
    {
        $this->money = auth()->user()->money;

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
        ];

        $response = $client->get('https://brapi.dev/api/quote/' . $stock . '?token=86wrdergHbq1wsQc8BrWNF', [
            'headers' => $headers,
            'decode_content' => false
        ]);

        $this->stock = json_decode($response->getBody())->results[0];

        $this->stock_name = $this->stock->symbol;
        $this->stock_price = $this->stock->regularMarketPrice;
        $this->totalAmount = $this->quantity * $this->stock_price;
    }

    public function index()
    {
        $this->isLoading = true;
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

             return redirect()->route('stocks.index')->with('status', $this->stock_name .' Comprada com sucesso!');
         } catch (\Exception $e) {
            return redirect()->route('stocks.index')->with('error', 'Não foi possível comprar: '. $this->stock_name);
         }
    }

    public function render()
    {
        return view('livewire.buy-form-manager', [
            'stock' => $this->stock,
        ]);	
    }
}

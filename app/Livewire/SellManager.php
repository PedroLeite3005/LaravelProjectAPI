<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\UserStock;

class SellManager extends Component
{
    protected User $user;

    public float $money;
    public $page = 1;
    public $stocksPerPage = 10;
    public $lastPage;
    public $searchTerm = '';
    public $userStocks;
    public $stock_id;

    public function mount()
    {
        $this->money = auth()->user()->money;
        $this->userStocks = auth()->user()->userStock;
        $this->stock_id = $this->userStocks->first()->id;
        $this->page = 1;
    }

    public function sellIndex()
    {
        /** @var User $user */
        $user = auth()->user();
        $userStocks = $user->userStock;

        $page = $this->page ?? 1;
        $userStocks = collect($this->userStocks);
    
        $this->lastPage = ceil($userStocks->count() / $this->stocksPerPage);
        $skip = ($page - 1) * $this->stocksPerPage;
        $userStocks = $userStocks->slice($skip, $this->stocksPerPage);

        $searchTerm = $this->searchTerm;
        if ($searchTerm) {
            $userStocks = $userStocks->filter(function ($s) use($searchTerm) {
                return Str::contains(strtoupper($s->stock_name), strtoupper($searchTerm));
            });
        }
    }
    public function sellStock(int $stockId, int $quantity, float $sellAmount, string $stockName)
    {
    
        $userStock = UserStock::find($stockId); 
        if (!$userStock) {
            return back()->with('error', 'Ação não encontrada');
        }

        try {
            DB::transaction(function() use ($userStock, $quantity, $sellAmount, $stockName) {
                /** @var User $user */
                $user = auth()->user();
                $userStock->update([
                'stock_quantity' => $userStock->stock_quantity - $quantity
                ]);

                if ($userStock->stock_quantity == 0) {
                    $userStock->delete(); 
                }

                $user->update([
                    'money' => $user->money + $sellAmount
                ]);

                $user->transactionHistory()->create([
                    'type' => 'venda',
                    'name' => $stockName, 
                    'quantity' => $quantity,
                    'price' => $sellAmount,
                ]);

                $this->money = $user->money;

                return back()->with('success', 'Venda registrada com sucesso');
            });
        
        } catch (\Exception $e) {
            back()->with('error', 'Houve algum erro ao registrar a venda');
            }
    }

    public function render()
    {      
        return view('livewire.sell-manager', [
            'userStocks' => $this->searchTerm,
        ]);
    }
}

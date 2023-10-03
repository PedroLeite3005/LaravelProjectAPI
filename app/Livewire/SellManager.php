<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserStock;
use Livewire\WithPagination;

class SellManager extends Component
{
    protected User $user;

    use WithPagination;

    private $userStocks;
    private float $money;

    public $stock_id;
    public $searchTerm = '';

    public function mount()
    {
        $this->money = auth()->user()->money;
    }
    
    public function sellIndex()
    {
        $this->userStocks = UserStock::where('user_id', auth()->user()->id)
                                    ->where('stock_name', 'like', '%' . $this->searchTerm . '%')
                                    ->paginate(10)
                                    ->onEachSide(0);
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
        
        } catch (\Exception) {
            back()->with('error', 'Houve algum erro ao registrar a venda');
            }
    }

    public function render()
    {
        $this->sellIndex();
        return view('livewire.sell-manager',[
            'userStocks' => $this->userStocks,
        ]);
    }
}

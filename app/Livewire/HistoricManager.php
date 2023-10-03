<?php

namespace App\Livewire;

use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Component;

class HistoricManager extends Component
{  
    use WithPagination;
    private $transactions;

    public $searchTerm = '';
    public $type = '';

    public function mount()
    {
        
    }

    public function paginate()
    {
        $this->transactions = TransactionHistory::where('user_id', auth()->user()->id)
                                ->where('name', 'like', '%' . $this->searchTerm . '%')
                                ->where('type', 'like', '%' . $this->type . '%')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15)
                                ->onEachSide(0);
    }

    public function render()
    {
        $this->paginate(15);
        return view('livewire.historic-manager',[
        'transactions' => $this->transactions,
        ]);
    }
}

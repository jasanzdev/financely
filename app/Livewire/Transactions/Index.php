<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Component;

class Index extends Component
{
    public $transactions;

    public function mount()
    {
        $user = auth()->id();
        $this->transactions = Transaction::take(8)
            ->where('user_id', $user)
            ->where('state', 'paid')
            ->whereMonth('date', date('m'))
            ->orderBy('date', 'desc')
            ->get();
    }

    public function delete(Transaction $transaction)
    {
        $transaction->delete();

        session()->flash('message', 'El registo ha sido eliminado del sistema.');
    }

    public function render()
    {
        return view('livewire.transactions.index');
    }
}

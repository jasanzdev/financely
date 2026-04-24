<?php

namespace App\Livewire\Transactions;

use App\Enums\TransactionState;
use App\Models\Transaction;
use Livewire\Component;

class Index extends Component
{
    public $transactions;

    public function mount()
    {
        $user = auth()->id();
        $this->transactions = Transaction::with('category')
            ->take(8)
            ->where('user_id', $user)
            ->where('state', TransactionState::Paid)
            ->where('date', '>=', now()->startOfMonth())
            ->where('date', '<', now()->addMonth()->startOfMonth())
            ->orderBy('date', 'desc')
            ->get();
    }

    public function delete(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        session()->flash('message', 'El registro ha sido eliminado del sistema.');
    }

    public function render()
    {
        return view('livewire.transactions.index');
    }
}

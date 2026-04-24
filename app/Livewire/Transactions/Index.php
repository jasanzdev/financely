<?php

namespace App\Livewire\Transactions;

use App\Enums\TransactionState;
use App\Models\Transaction;
use Livewire\Component;

class Index extends Component
{
    public function delete(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        session()->flash('message', 'El registro ha sido eliminado del sistema.');
    }

    public function render()
    {
        $transactions = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->where('state', TransactionState::Paid)
            ->where('date', '>=', now()->startOfMonth())
            ->where('date', '<', now()->addMonth()->startOfMonth())
            ->orderBy('date', 'desc')
            ->take(8)
            ->get();

        return view('livewire.transactions.index', compact('transactions'));
    }
}

<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class PendingTransactions extends Component
{
    use WithPagination;

    public Collection $receivables;

    public Collection $payables;
    public string $selectedTab = 'all';

    public function selectAll()
    {
        $this->selectedTab = 'all';
    }

    public function selectReceivable()
    {
        $this->selectedTab = 'receivable';
    }

    public function selectPayable()
    {
        $this->selectedTab = 'payable';
    }

    public function changeStatus(Transaction $transaction)
    {
        $transaction->update(['state' => 'paid', 'expected_payment_date' => now()]);
    }

    public function delete(Transaction $transaction)
    {
        $transaction->delete();

        session()->flash('message', 'El registo ha sido eliminado del sistema.');
    }

    public function render()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->where('state', 'pending');

        $this->receivables = (clone $query)->where('type', 'income')->get();
        $this->payables = (clone $query)->where('type', 'expense')->get();

        $query = match ($this->selectedTab) {
            'all' => $query,
            'receivable' => $query->where('type', 'income'),
            'payable' => $query->where('type', 'expense'),
        };

        $transactions = $query
            ->orderBy('expected_payment_date')
            ->orderBy('date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.transactions.pending-transactions', compact('transactions'));
    }
}

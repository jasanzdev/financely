<?php

namespace App\Livewire\Transactions;

use App\Enums\TransactionState;
use App\Enums\TransactionType;
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
        $this->authorize('update', $transaction);
        $transaction->update(['state' => TransactionState::Paid, 'expected_payment_date' => null]);
    }

    public function delete(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        session()->flash('message', 'El registro ha sido eliminado del sistema.');
    }

    public function render()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->where('state', TransactionState::Pending)
            ->where('date', '<', now()->addMonth()->startOfMonth());

        $this->receivables = (clone $query)->where('type', TransactionType::Income)->get();
        $this->payables = (clone $query)->where('type', TransactionType::Expense)->get();

        $query = match ($this->selectedTab) {
            'all' => $query,
            'receivable' => $query->where('type', TransactionType::Income),
            'payable' => $query->where('type', TransactionType::Expense),
        };

        $transactions = $query
            ->with('category')
            ->orderBy('date')
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('livewire.transactions.pending-transactions', compact('transactions'));
    }
}

<?php

namespace App\Livewire\Metrics;

use App\Models\Transaction;
use Illuminate\View\View;
use Livewire\Component;

class Grid extends Component
{
    public $total_income;
    public $total_expense;
    public $receivable_transactions;
    public $payable_transactions;

    public $modalIsOpen = false;
    public $modalType = null;

    public function openModal($type)
    {
        $this->modalType = $type;
        $this->modalIsOpen = true;
    }

    public function closeModal()
    {
        $this->modalIsOpen = false;
        $this->modalType = null;
    }

    public function changeStatus(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $transaction->update(['state' => 'paid', 'expected_payment_date' => null]);
    }

    public function render(): View
    {
        $base = Transaction::where('user_id', auth()->id());

        // Pending transactions up to the end of the current month — one query,
        // partitioned in PHP to avoid a second round-trip.
        $allPending = (clone $base)
            ->where('state', 'pending')
            ->where('date', '<', now()->addMonth()->startOfMonth())
            ->orderBy('date')
            ->get();

        $this->receivable_transactions = $allPending->where('type', 'income');
        $this->payable_transactions    = $allPending->where('type', 'expense');

        // Current-month paid totals — single query with conditional aggregation
        // instead of two separate sum() calls.
        $totals = (clone $base)
            ->where('state', 'paid')
            ->where('date', '>=', now()->startOfMonth())
            ->where('date', '<', now()->addMonth()->startOfMonth())
            ->selectRaw('
                SUM(CASE WHEN type = "income"  THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense
            ')
            ->first();

        $this->total_income  = $totals->total_income  ?? 0;
        $this->total_expense = $totals->total_expense ?? 0;

        return view('livewire.metrics.grid');
    }
}

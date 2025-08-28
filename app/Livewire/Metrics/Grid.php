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
        $transaction->update(['state' => 'paid', 'expected_payment_date' => now()]);
        $this->modalIsOpen = true;
    }

    public function render(): View
    {
        $transactions = Transaction::where('user_id', auth()->id());

        $pending_transactions = (clone $transactions)
            ->where('state', 'pending')
            ->orderBy('expected_payment_date');

        $this->receivable_transactions = (clone $pending_transactions)
            ->where('type', 'income')
            ->get();

        $this->payable_transactions = (clone $pending_transactions)
            ->where('type', 'expense')
            ->get();

        $paid_transactions = (clone $transactions)
            ->where('state', 'paid')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);

        $this->total_income = (clone $paid_transactions)
            ->where('type', 'income')
            ->sum('amount');

        $this->total_expense = (clone $paid_transactions)
            ->where('type', 'expense')
            ->sum('amount');

        return view('livewire.metrics.grid');
    }
}

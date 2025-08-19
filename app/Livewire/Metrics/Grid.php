<?php

namespace App\Livewire\Metrics;

use App\Models\Transaction;
use Illuminate\View\View;
use Livewire\Component;

class Grid extends Component
{
    public $total_income;
    public $total_expense;

    public $total_transactions;

    public function mount(): void
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $this->total_income = $transactions->where('type', 'income')->sum('amount');
        $this->total_expense = $transactions->where('type', 'expense')->sum('amount');
        $this->total_transactions = count($transactions);
    }

    public function render(): View
    {
        return view('livewire.metrics.grid');
    }
}

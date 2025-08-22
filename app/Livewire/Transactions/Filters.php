<?php

namespace App\Livewire\Transactions;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Filters extends Component
{
    use WithPagination;

    public string $selectedTab = 'days';
    public int $selectedMonth;
    public int $selectedYear;

    public Collection $categories;
    public string|null $selectedCategory = null;
    public float $incomes = 0;
    public float $expenses = 0;

    public string $showType = 'all';

    public function mount(): void
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->month;
        $this->latestDays(7);
        $this->categories = Category::where('user_id', auth()->id())->get();
    }

    public function latestDays($days): void
    {
        $this->selectedTab = 'days';
        $this->showType = 'all';
        $this->selectedCategory = null;
    }

    public function latestMonth($month): void
    {
        $this->showType = 'all';
        $this->selectedTab = $month === now()->month ? 'current-month' : 'previous-month';
        $this->selectedCategory = null;
    }

    public function historical(): void
    {
        $this->showType = 'all';
        $this->selectedTab = 'historical';
        $this->selectedCategory = null;
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function delete(Transaction $transaction)
    {
        $transaction->delete();

        session()->flash('message', 'El registo ha sido eliminado del sistema.');

        $this->redirect(url()->previous() ?? route('dashboard'), navigate: true);
    }

    public function render(): View
    {
        $query = Transaction::where('user_id', auth()->id());
        
        $query = match ($this->selectedTab) {
            'days' => $query->where('date', '>=', now()
                ->subDays(7)),
            'current-month' => $query->whereMonth('date', now()->month)
                ->whereYear('date', $this->selectedYear),
            'previous-month' => $query->whereMonth('date', now()->subMonth()->month)
                ->whereYear('date', $this->selectedYear),
            'historical' => $query->whereMonth('date', $this->selectedMonth)
                ->whereYear('date', $this->selectedYear),
        };

        if ($this->selectedCategory && $this->selectedCategory !== 'all')
            $query->where('category_id', $this->selectedCategory);

        if ($this->showType !== 'all')
            $query->where('type', $this->showType);

        $this->expenses = (clone $query)->where('type', 'expense')->sum('amount');
        $this->incomes = (clone $query)->where('type', 'income')->sum('amount');
        
        $transactions = $query
            ->orderBy('date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.transactions.filters', compact('transactions'));
    }
}

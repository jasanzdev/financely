<?php

namespace App\Livewire\Transactions;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
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
    public string $search = '';

    public function mount(): void
    {
        $this->selectedYear  = now()->year;
        $this->selectedMonth = now()->month;
        $this->latestDays(7);
        $this->categories = Category::where('user_id', auth()->id())->get();
    }

    public function latestDays($days): void
    {
        $this->selectedTab = 'days';
    }

    public function latestMonth($month): void
    {
        $this->selectedTab = $month === now()->month ? 'current-month' : 'previous-month';
    }

    public function historical(): void
    {
        $this->selectedTab = 'historical';
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        session()->flash('message', 'El registro ha sido eliminado del sistema.');
    }

    public function render(): View
    {
        $query = Transaction::where('user_id', auth()->id())->where('state', 'paid');

        // Use explicit date ranges instead of MONTH()/YEAR() functions so MySQL
        // can perform a range scan on the (user_id, state, date) composite index.
        $query = match ($this->selectedTab) {
            'days' => $query->where('date', '>=', now()->subDays(7)),

            'current-month' => $query
                ->where('date', '>=', Carbon::create($this->selectedYear, now()->month, 1))
                ->where('date', '<',  Carbon::create($this->selectedYear, now()->month, 1)->addMonthNoOverflow()),

            'previous-month' => $query
                ->where('date', '>=', Carbon::create($this->selectedYear, now()->subMonthNoOverflow()->month, 1))
                ->where('date', '<',  Carbon::create($this->selectedYear, now()->subMonthNoOverflow()->month, 1)->addMonthNoOverflow()),

            'historical' => $query
                ->where('date', '>=', Carbon::create($this->selectedYear, $this->selectedMonth, 1))
                ->where('date', '<',  Carbon::create($this->selectedYear, $this->selectedMonth, 1)->addMonthNoOverflow()),
        };

        if ($this->selectedCategory && $this->selectedCategory !== 'all')
            $query->where('category_id', $this->selectedCategory);

        if ($this->showType !== 'all')
            $query->where('type', $this->showType);

        if ($this->search !== '')
            $query->where('description', 'like', '%' . $this->search . '%');

        $totals = (clone $query)
            ->selectRaw('
                SUM(CASE WHEN type = "income"  THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense
            ')
            ->first();

        $this->incomes  = $totals->total_income  ?? 0;
        $this->expenses = $totals->total_expense ?? 0;

        $transactions = $query
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('livewire.transactions.filters', compact('transactions'));
    }
}

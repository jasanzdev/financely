<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use App\Models\Obligation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ObligationForm extends Form
{
    public ?Obligation $obligation = null;

    public string $name = '';

    public float $amount = 0.0;

    public string $description = '';
    public int $limit_day = 0;

    public string $category = '';
    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:50',
                Rule::unique('obligations', 'name')
                    ->where('user_id', auth()->id())
                    ->ignore($this->obligation?->id),
            ],
            'category' => ['required', 'string', 'min:3', 'max:50'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'min:5', 'max:255'],
            'limit_day' => ['required', 'integer', 'min:1'],
        ];
    }

    public function setObligation(Obligation $obligation): void
    {
        $this->obligation = $obligation;
        $this->name = $obligation->name;
        $this->amount = $obligation->amount;
        $this->description = $obligation->description;
        $this->limit_day = $obligation->limit_day;
        $this->category = $obligation->category;
        $this->is_active = $obligation->is_active;
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();
        return Obligation::create($validated);
    }

    public function update()
    {
        $this->validate();
        $category = Category::firstOrCreate(
            ['slug' => 'obligaciones-mensuales'],
            [
                'category' => 'Obligaciones Mensuales',
                'description' => 'Pagos fijos mensuales',
                'user_id' => auth()->id()
            ]
        );
        $transactions = Transaction::where('user_id', auth()->id())
            ->where('category_id', $category->id)
            ->where('state', 'pending')
            ->where('expected_payment_date', '>=', Carbon::today())
            ->where('description', $this->obligation->name)
            ->get();
        $this->obligation->update($this->all());
        $expected_payment_date = Carbon::today()->setDay($this->limit_day);
        foreach ($transactions as $transaction) {
            $transaction->update([
                'amount' => $this->amount,
                'description' => $this->name,
                'expected_payment_date' => $expected_payment_date,
            ]);
        }
    }

    public function createTransactions(Obligation $obligation): void
    {
        $category = Category::firstOrCreate(
            ['category' => 'Obligaciones Mensuales'],
            [
                'category' => 'Obligaciones Mensuales',
                'description' => 'Pagos fijos mensuales',
                'user_id' => auth()->id()
            ]
        );
        for ($month = now()->month; $month <= 12; $month++) {
            $date = Carbon::today()->setMonth($month);
            $expected_payment_date = Carbon::today()->setMonth($month)->setDay($obligation->limit_day);
            Transaction::create([
                'type' => 'expense',
                'amount' => $obligation->amount,
                'description' => $obligation->name,
                'state' => 'pending',
                'expected_payment_date' => $expected_payment_date,
                'date' => $date,
                'user_id' => auth()->id(),
                'category_id' => $category->id,
            ]);
        }
    }

    public function removeTransactions(Obligation $obligation): void
    {
        $category = Category::where('slug', 'obligaciones-mensuales')
            ->where('user_id', auth()->id())
            ->first();
        $transactions = Transaction::where('user_id', auth()->id())
            ->where('category_id', $category->id)
            ->where('state', 'pending')
            ->where('date', '>=', Carbon::today())
            ->where('description', $obligation->name)
            ->get();
        foreach ($transactions as $transaction) {
            $transaction->delete();
        }
    }
}

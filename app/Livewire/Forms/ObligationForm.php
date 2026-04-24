<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use App\Models\Obligation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ObligationForm extends Form
{
    private const OBLIGATIONS_CATEGORY_SLUG = 'obligaciones-mensuales';

    public ?Obligation $obligation = null;

    public string $name = '';

    public float $amount = 0.0;

    public string $description = '';

    public int $limit_day = 0;

    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:50',
                Rule::unique('obligations', 'name')
                    ->where('user_id', auth()->id())
                    ->ignore($this->obligation?->id),
            ],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'min:5', 'max:255'],
            'limit_day' => ['required', 'integer', 'min:1', 'max:28'],
        ];
    }

    public function setObligation(Obligation $obligation): void
    {
        $this->obligation = $obligation;
        $this->name = $obligation->name;
        $this->amount = $obligation->amount;
        $this->description = $obligation->description;
        $this->limit_day = $obligation->limit_day;
        $this->is_active = $obligation->is_active;
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();

        return Obligation::create($validated);
    }

    public function update(): void
    {
        $this->validate();

        DB::transaction(function () {
            $category = $this->resolveObligationsCategory();
            $oldName = $this->obligation->name;

            $this->obligation->update($this->all());

            Transaction::where('user_id', auth()->id())
                ->where('category_id', $category->id)
                ->where('state', 'pending')
                ->where('description', $oldName)
                ->update([
                    'amount' => $this->amount,
                    'description' => $this->name,
                    'expected_payment_date' => Carbon::today()->setDay($this->limit_day),
                ]);
        });
    }

    public function createTransactions(Obligation $obligation): void
    {
        $category = $this->resolveObligationsCategory();
        $today = Carbon::today();
        $year = $today->year;

        $startMonth = $today->day >= $obligation->limit_day
            ? $today->month + 1
            : $today->month;

        // Past December with payment day elapsed: nothing to generate this cycle.
        // Rolling forward into next year is the responsibility of the monthly
        // regeneration command (roadmap PR #5).
        if ($startMonth > 12) {
            return;
        }

        $rows = [];
        for ($month = $startMonth; $month <= 12; $month++) {
            // Build dates with Carbon::create($year, $month, $day) instead of mutating
            // today(). Mutating via setMonth() overflows when today's day exceeds the
            // target month's days (e.g. Jan 31 → setMonth(2) = Mar 3 in default Carbon),
            // placing transactions in the wrong month.
            $rows[] = [
                'id' => (string) Str::uuid(),
                'type' => 'expense',
                'amount' => $obligation->amount,
                'description' => $obligation->name,
                'state' => 'pending',
                'expected_payment_date' => Carbon::create($year, $month, $obligation->limit_day),
                'date' => Carbon::create($year, $month, 1),
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($rows)) {
            Transaction::insert($rows);
        }
    }

    public function removeTransactions(Obligation $obligation): void
    {
        $category = Category::where('slug', self::OBLIGATIONS_CATEGORY_SLUG)
            ->where('user_id', auth()->id())
            ->first();

        if (! $category) {
            return;
        }

        Transaction::where('user_id', auth()->id())
            ->where('category_id', $category->id)
            ->where('state', 'pending')
            ->where('description', $obligation->name)
            ->delete();
    }

    // Finds or creates the "Obligaciones Mensuales" category for the current user.
    private function resolveObligationsCategory(): Category
    {
        return Category::firstOrCreate(
            ['slug' => self::OBLIGATIONS_CATEGORY_SLUG, 'user_id' => auth()->id()],
            [
                'category' => 'Obligaciones Mensuales',
                'description' => 'Pagos fijos mensuales',
                'user_id' => auth()->id(),
            ]
        );
    }
}

<?php

namespace App\Livewire\Forms;

use App\Enums\TransactionState;
use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Validation\Rule;
use Livewire\Form;

class TransactionForm extends Form
{
    public ?Transaction $transaction;

    public $type;

    public $amount;

    public $description;

    public $state = true;

    public $expected_payment_date;

    public $category_id;

    public $date;

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(TransactionType::class)],
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'expected_payment_date' => 'nullable',
            'date' => 'required|before_or_equal:today',
            // Validates existence AND ownership — prevents assigning another user's category
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('user_id', auth()->id()),
            ],
        ];
    }

    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        // Blade bindings expect the raw string value (e.g. 'income'), not the enum instance.
        $this->type = $transaction->type->value;
        $this->amount = $transaction->amount;
        $this->description = $transaction->description;
        $this->date = $transaction->formatted_date;
        $this->category_id = $transaction->category_id;
        $this->state = $transaction->isPaid();
        $this->expected_payment_date = $transaction->formatted_expected_payment_date;
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();
        $validated['state'] = $this->state ? TransactionState::Paid : TransactionState::Pending;
        Transaction::create($validated);
    }

    public function update()
    {
        $validated = $this->validate();
        $validated['expected_payment_date'] = ! blank($validated['expected_payment_date']) ? $validated['expected_payment_date'] : null;
        $validated['state'] = $this->state ? TransactionState::Paid : TransactionState::Pending;
        $this->transaction->update($validated);
    }
}

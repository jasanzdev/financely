<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public ?Transaction $transaction;

    #[Validate('required|in:income,expense')]
    public $type;

    #[Validate('required|min:0|numeric')]
    public $amount;

    #[Validate('required|string|max:255')]
    public $description;

    public $state = true;

    #[Validate('nullable')]
    public $expected_payment_date;

    #[Validate('required|exists:categories,id')]
    public $category_id;

    #[Validate('required|before_or_equal:today')]
    public $date;


    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->type = $transaction->type;
        $this->amount = $transaction->amount;
        $this->description = $transaction->description;
        $this->date = $transaction->formatted_date;
        $this->category_id = $transaction->category->id;
        $this->state = $transaction->state === 'paid';
        $this->expected_payment_date = $transaction->formatted_expected_payment_date;
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();
        $validated['state'] = $this->state ? 'paid' : 'pending';
        Transaction::create($validated);
    }

    public function update()
    {
        $validated = $this->validate();
        $validated['expected_payment_date'] = !blank($validated['expected_payment_date']) ? $validated['expected_payment_date'] : null;
        $validated['state'] = $this->state ? 'paid' : 'pending';
        $this->transaction->update($validated);
    }
}

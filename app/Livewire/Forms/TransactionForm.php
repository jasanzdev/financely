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
        $this->date = $transaction->date;
        $this->category_id = $transaction->category->id;
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();
        Transaction::create($validated);
    }

    public function update()
    {
        $this->validate();
        $this->transaction->update($this->all());
    }
}

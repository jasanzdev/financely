<?php

namespace App\Livewire\Transactions;

use App\Livewire\Forms\TransactionForm;
use App\Models\Transaction;
use Livewire\Component;

class Update extends Component
{
    public TransactionForm $form;

    public function mount(Transaction $transaction)
    {
        $this->form->setTransaction($transaction);
    }

    public function edit()
    {
        $this->form->update();
        session()->flash('message', 'Transacción actualizada con exito');
        $this->redirect(route('transaction.filters'), navigate: true);
    }

    public function render()
    {
        return view('livewire.transactions.update');
    }
}

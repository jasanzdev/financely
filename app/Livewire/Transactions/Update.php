<?php

namespace App\Livewire\Transactions;

use App\Livewire\Forms\TransactionForm;
use App\Models\Transaction;
use Livewire\Component;

class Update extends Component
{
    public TransactionForm $form;

    public $redirectTo;

    public function mount(Transaction $transaction)
    {
        $this->form->setTransaction($transaction);
        $this->redirectTo = request()->query('from', 'transactions');
    }

    public function edit()
    {
        $this->form->update();
        session()->flash('message', 'Transacción actualizada con exito');
        $this->redirect(route(match ($this->redirectTo) {
            'transactions' => 'transaction.filters',
            'pending' => 'pending.transactions',
            default => 'dashboard',
        }), navigate: true);
    }

    public function render()
    {
        return view('livewire.transactions.update');
    }
}

<?php

namespace App\Livewire\Transactions;

use App\Livewire\Forms\TransactionForm;
use App\Models\Category;
use Livewire\Component;

class Create extends Component
{
    public TransactionForm $form;
    public $categories = [];

    public function mount()
    {
        $this->categories = Category::all()->where('user_id', auth()->id());
    }

    public function save()
    {
        $this->form->store();

        session()->flash('message', 'Registro de transacción completado exitosamente.');

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.transactions.create');
    }
}

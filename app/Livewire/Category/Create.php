<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\CategoryForm;
use Livewire\Component;

class Create extends Component
{
    public CategoryForm $form;

    public function save()
    {
        $this->form->store();

        session()->flash('message', 'Se ha añadido la nueva categoría.');

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.category.create');
    }
}

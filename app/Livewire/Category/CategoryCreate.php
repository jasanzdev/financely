<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\CategoryForm;
use Livewire\Component;

class CategoryCreate extends Component
{
    public CategoryForm $form;
    public $redirectTo = 'transactions';

    public function mount($redirectTo = 'categories')
    {
        $this->redirectTo = $redirectTo;
    }

    public function save()
    {
        $this->form->store();

        session()->flash('message', 'Se ha añadido la nueva categoría.');

        $this->redirect($this->redirectTo === 'transactions'
            ? route('dashboard')
            : route('category.index'),
            navigate: true);
    }

    public function render()
    {
        return view('livewire.category.category-create');
    }
}

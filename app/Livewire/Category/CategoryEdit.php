<?php

namespace App\Livewire\Category;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Component;

class CategoryEdit extends Component
{
    public CategoryForm $form;

    public function mount(Category $category)
    {
        $this->form->setCategory($category);
    }

    public function edit()
    {
        $this->form->update();
        session()->flash('message', 'Categoría actualizada con exito');
        $this->redirect(route('category.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.category.category-edit');
    }
}

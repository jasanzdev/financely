<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryIndex extends Component
{
    public function toggleActive(Category $category)
    {
        $this->authorize('update', $category);
        $category->update(['is_active' => ! $category->is_active]);
    }

    public function delete(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->transactions()->exists()) {
            session()->flash('message', 'No se ha podido eliminar la categoría, mueva las transacciones existentes');
            $this->redirect(route('category.index'), navigate: true);

            return;
        }

        $category->delete();

        session()->flash('message', 'El registro ha sido eliminado del sistema.');

        $this->redirect(route('category.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::where('user_id', auth()->id())->get();

        return view('livewire.category.category-index', compact('categories'));
    }
}

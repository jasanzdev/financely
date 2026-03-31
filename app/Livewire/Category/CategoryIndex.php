<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CategoryIndex extends Component
{
    public Collection|null $categories = null;

    public function mount()
    {
        $this->categories = Category::where('user_id', auth()->id())->get();
    }

    public function toggleActive(Category $category)
    {
        $this->authorize('update', $category);
        $category->update(['is_active' => !$category->is_active]);
        $this->categories = Category::where('user_id', auth()->id())->get();
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

        session()->flash('message', 'El registo ha sido eliminado del sistema.');

        $this->redirect(route('category.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.category.category-index');
    }
}

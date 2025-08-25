<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $categorySelected = null;

    public string|null $category = '';

    public string|null $description = '';

    public function rules(): array
    {
        return [
            'category' => [
                'required',
                'string',
                'min:3',
                Rule::unique('categories', 'category')
                    ->where('user_id', auth()->id())
                    ->ignore($this->categorySelected?->id),
            ],
            'description' => [
                'nullable',
                'string',
                'min:5',
                'max:255'
            ]
        ];
    }

    public function setCategory(Category $category): void
    {
        $this->categorySelected = $category;
        $this->category = $category->category;
        $this->description = $category->description;
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();
        Category::create($validated);
    }

    public function update()
    {
        $this->validate();
        $this->categorySelected->update($this->all());
    }
}

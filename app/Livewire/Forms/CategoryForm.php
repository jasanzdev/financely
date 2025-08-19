<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CategoryForm extends Form
{
    public string $category = '';

    public string $description = '';

    public function rules(): array
    {
        return [
            'category' => [
                'required',
                'string',
                'min:3',
                Rule::unique('categories', 'category')
                    ->where('user_id', auth()->id())
            ],
            'description' => [
                'nullable',
                'string',
                'min:5',
                'max:255'
            ]
        ];
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();
        Category::create($validated);
    }
}

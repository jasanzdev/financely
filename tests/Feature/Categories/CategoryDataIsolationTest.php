<?php

use App\Livewire\Category\CategoryIndex;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user only sees their own categories in the index', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    Category::factory()->for($userA)->create(['category' => 'Mis Gastos']);
    Category::factory()->for($userB)->create(['category' => 'Gastos Ajenos']);

    Livewire::actingAs($userA)
        ->test(CategoryIndex::class)
        ->assertSee('Mis Gastos')
        ->assertDontSee('Gastos Ajenos');
});

test('user only sees their own categories count in the index', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    Category::factory()->for($userA)->count(2)->create();
    Category::factory()->for($userB)->count(5)->create();

    $component = Livewire::actingAs($userA)->test(CategoryIndex::class);

    expect($component->viewData('categories'))->toHaveCount(2);
});

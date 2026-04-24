<?php

use App\Livewire\Category\CategoryEdit;
use App\Livewire\Category\CategoryIndex;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- Route access ---

test('guests are redirected from category index', function () {
    $this->get(route('category.index'))->assertRedirect('/login');
});

test('guests are redirected from category edit', function () {
    $category = Category::factory()->create();
    $this->get(route('category.edit', $category))->assertRedirect('/login');
});

// --- CategoryEdit mount authorization ---

test('owner can visit category edit page', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('category.edit', $category))
        ->assertOk();
});

test('other user cannot visit category edit page', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $category = Category::factory()->for($owner)->create();

    $this->actingAs($attacker)
        ->get(route('category.edit', $category))
        ->assertForbidden();
});

// --- CategoryIndex::delete authorization ---

test('owner can delete their category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CategoryIndex::class)
        ->call('delete', $category->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('other user cannot delete a category they do not own', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $category = Category::factory()->for($owner)->create();

    Livewire::actingAs($attacker)
        ->test(CategoryIndex::class)
        ->call('delete', $category->id);

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('cannot delete category that has transactions', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    Transaction::factory()->for($user)->for($category)->create();

    Livewire::actingAs($user)
        ->test(CategoryIndex::class)
        ->call('delete', $category->id);

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

// --- CategoryEdit::edit authorization ---

test('owner can update their category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CategoryEdit::class, ['category' => $category])
        ->set('form.category', 'Updated Category')
        ->set('form.description', 'Updated description here')
        ->call('edit')
        ->assertHasNoErrors()
        ->assertRedirect(route('category.index'));

    $this->assertDatabaseHas('categories', ['id' => $category->id, 'category' => 'Updated Category']);
});

test('other user cannot update a category they do not own', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $category = Category::factory()->for($owner)->create(['category' => 'Original Name']);

    // Authorization is enforced at mount() — the edit page returns 403
    $this->actingAs($attacker)
        ->get(route('category.edit', $category))
        ->assertForbidden();

    $this->assertDatabaseHas('categories', ['id' => $category->id, 'category' => 'Original Name']);
});

// --- Staleness regression ---
// toggleActive used to mutate the DB and then re-query manually to keep the
// view in sync. If the manual re-query were ever removed — or if a developer
// added a similar action without remembering it — the view would show the
// pre-toggle state until reload. Loading in render() makes this automatic.

test('toggling a category active status reflects in the rendered list without manual refetch', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'category' => 'ToggleTarget',
        'is_active' => true,
    ]);

    $component = Livewire::actingAs($user)->test(CategoryIndex::class);

    $component->call('toggleActive', $category->id)
        ->assertHasNoErrors();

    $rendered = $component->viewData('categories')->firstWhere('id', $category->id);

    expect($rendered->is_active)->toBeFalse();
});

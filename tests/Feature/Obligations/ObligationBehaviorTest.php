<?php

use App\Livewire\Obligations\MonthlyBillings;
use App\Models\Category;
use App\Models\Obligation;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('deleting an obligation removes its pending transactions', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'category' => 'Obligaciones Mensuales',
        'slug'     => 'obligaciones-mensuales',
    ]);
    $obligation = Obligation::factory()->for($user)->create(['name' => 'Alquiler']);

    Transaction::factory()
        ->for($user)
        ->for($category)
        ->pending()
        ->create(['description' => 'Alquiler']);

    $countBefore = Transaction::where('user_id', $user->id)
        ->where('description', 'Alquiler')
        ->where('state', 'pending')
        ->count();

    expect($countBefore)->toBe(1);

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->call('delete', $obligation->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('obligations', ['id' => $obligation->id]);
    $this->assertDatabaseMissing('transactions', [
        'user_id'     => $user->id,
        'description' => 'Alquiler',
        'state'       => 'pending',
        'category_id' => $category->id,
    ]);
});

test('deactivating an obligation removes its pending transactions', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'category' => 'Obligaciones Mensuales',
        'slug'     => 'obligaciones-mensuales',
    ]);
    $obligation = Obligation::factory()->for($user)->create(['name' => 'Seguro', 'is_active' => true]);

    Transaction::factory()
        ->for($user)
        ->for($category)
        ->pending()
        ->create(['description' => 'Seguro']);

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->call('changeStatus', $obligation->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('obligations', ['id' => $obligation->id, 'is_active' => false]);
    $this->assertDatabaseMissing('transactions', [
        'user_id'     => $user->id,
        'description' => 'Seguro',
        'state'       => 'pending',
    ]);
});

test('activating an obligation creates pending transactions', function () {
    $user = User::factory()->create();
    $obligation = Obligation::factory()->for($user)->inactive()->create([
        'name'      => 'Internet',
        'amount'    => 50,
        'limit_day' => 28,
    ]);

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->call('changeStatus', $obligation->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('obligations', ['id' => $obligation->id, 'is_active' => true]);

    $pending = Transaction::where('user_id', $user->id)
        ->where('description', 'Internet')
        ->where('state', 'pending')
        ->count();

    expect($pending)->toBeGreaterThan(0);
});

test('saving an obligation creates pending transactions for the rest of the year', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'Electricidad')
        ->set('form.amount', 80)
        ->set('form.description', 'Pago mensual de electricidad')
        ->set('form.limit_day', 15)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('obligations', ['user_id' => $user->id, 'name' => 'Electricidad']);

    $pending = Transaction::where('user_id', $user->id)
        ->where('description', 'Electricidad')
        ->where('state', 'pending')
        ->count();

    expect($pending)->toBeGreaterThan(0);
});

test('limit_day above 28 is rejected', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'Obligacion Test')
        ->set('form.amount', 100)
        ->set('form.description', 'Una descripcion valida aqui')
        ->set('form.limit_day', 29)
        ->call('save')
        ->assertHasErrors(['form.limit_day']);
});

test('obligation data is isolated between users', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    Obligation::factory()->for($userB)->create(['name' => 'Secreto de B']);

    $component = Livewire::actingAs($userA)->test(MonthlyBillings::class);

    // The rendered view should not contain user B's obligation
    $component->assertDontSee('Secreto de B');
});

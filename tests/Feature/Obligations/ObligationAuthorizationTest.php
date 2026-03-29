<?php

use App\Livewire\Obligations\MonthlyBillings;
use App\Models\Obligation;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- Route access ---

test('guests are redirected from obligations page', function () {
    $this->get(route('monthly.billings'))->assertRedirect('/login');
});

test('authenticated user can visit obligations page', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('monthly.billings'))->assertOk();
});

// --- openModal authorization ---

test('owner can open edit modal for their obligation', function () {
    $user = User::factory()->create();
    $obligation = Obligation::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->call('openModal', $obligation->id)
        ->assertSet('obligationModalIsOpen', true)
        ->assertHasNoErrors();
});

test('other user cannot open edit modal for obligation they do not own', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $obligation = Obligation::factory()->for($owner)->create();

    $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    Livewire::actingAs($attacker)
        ->test(MonthlyBillings::class)
        ->call('openModal', $obligation->id);
});

// --- changeStatus authorization ---

test('owner can toggle obligation status', function () {
    $user = User::factory()->create();
    $obligation = Obligation::factory()->for($user)->inactive()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->call('changeStatus', $obligation->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('obligations', ['id' => $obligation->id, 'is_active' => true]);
});

test('other user cannot change obligation status', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $obligation = Obligation::factory()->for($owner)->create(['is_active' => true]);

    Livewire::actingAs($attacker)
        ->test(MonthlyBillings::class)
        ->call('changeStatus', $obligation->id);

    $this->assertDatabaseHas('obligations', ['id' => $obligation->id, 'is_active' => true]);
});

// --- delete authorization ---

test('owner can delete their obligation', function () {
    $user = User::factory()->create();
    $obligation = Obligation::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->call('delete', $obligation->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('obligations', ['id' => $obligation->id]);
});

test('other user cannot delete obligation they do not own', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $obligation = Obligation::factory()->for($owner)->create();

    Livewire::actingAs($attacker)
        ->test(MonthlyBillings::class)
        ->call('delete', $obligation->id);

    $this->assertDatabaseHas('obligations', ['id' => $obligation->id]);
});

<?php

use App\Livewire\Metrics\Grid;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- Authorization ---

test('other user cannot mark a transaction as paid via grid', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($owner)
        ->for(Category::factory()->for($owner))
        ->pending()
        ->create();

    Livewire::actingAs($attacker)
        ->test(Grid::class)
        ->call('changeStatus', $transaction->id);

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'state' => 'pending']);
});

test('owner can mark their pending transaction as paid via grid', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->pending()
        ->create(['date' => now()]);

    Livewire::actingAs($user)
        ->test(Grid::class)
        ->call('changeStatus', $transaction->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'state' => 'paid']);
});

// --- expected_payment_date cleared on status change ---

test('marking transaction as paid sets expected_payment_date to null', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->pending()
        ->create(['date' => now()]);

    Livewire::actingAs($user)
        ->test(Grid::class)
        ->call('changeStatus', $transaction->id);

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'state' => 'paid',
        'expected_payment_date' => null,
    ]);
});

// --- Data isolation ---

test('grid totals only include the authenticated user transactions', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->create([
            'type' => 'income',
            'amount' => 1000,
            'state' => 'paid',
            'date' => now(),
        ]);

    Transaction::factory()
        ->for($otherUser)
        ->for(Category::factory()->for($otherUser))
        ->create([
            'type' => 'income',
            'amount' => 9999,
            'state' => 'paid',
            'date' => now(),
        ]);

    $component = Livewire::actingAs($user)->test(Grid::class);

    expect((float) $component->viewData('total_income'))->toBe(1000.0);
});

test('grid pending transactions only include the authenticated user transactions', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->pending()
        ->create(['date' => now(), 'type' => 'expense']);

    Transaction::factory()
        ->for($otherUser)
        ->for(Category::factory()->for($otherUser))
        ->pending()
        ->create(['date' => now(), 'type' => 'expense']);

    $component = Livewire::actingAs($user)->test(Grid::class);

    expect($component->viewData('payable_transactions'))->toHaveCount(1);
});

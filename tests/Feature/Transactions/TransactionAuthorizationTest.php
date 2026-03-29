<?php

use App\Livewire\Transactions\Filters;
use App\Livewire\Transactions\Index;
use App\Livewire\Transactions\PendingTransactions;
use App\Livewire\Transactions\Update;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- Route access ---

test('guests are redirected from transaction edit page', function () {
    $transaction = Transaction::factory()->create();
    $this->get(route('transaction.edit', $transaction))->assertRedirect('/login');
});

test('guests are redirected from transaction filters page', function () {
    $this->get(route('transaction.filters'))->assertRedirect('/login');
});

test('guests are redirected from pending transactions page', function () {
    $this->get(route('pending.transactions'))->assertRedirect('/login');
});

// --- Update::mount authorization ---

test('owner can visit transaction edit page', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()->for($user)->for(Category::factory()->for($user))->create();

    $this->actingAs($user)
        ->get(route('transaction.edit', $transaction))
        ->assertOk();
});

test('other user cannot visit transaction edit page', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $transaction = Transaction::factory()->for($owner)->for(Category::factory()->for($owner))->create();

    $this->actingAs($attacker)
        ->get(route('transaction.edit', $transaction))
        ->assertForbidden();
});

// --- Index::delete authorization ---

test('owner can delete transaction from index', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->create(['state' => 'paid', 'date' => now()]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->call('delete', $transaction->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
});

test('other user cannot delete transaction from index', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($owner)
        ->for(Category::factory()->for($owner))
        ->create(['state' => 'paid', 'date' => now()]);

    Livewire::actingAs($attacker)
        ->test(Index::class)
        ->call('delete', $transaction->id);

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
});

// --- Filters::delete authorization ---

test('owner can delete transaction from filters', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->create(['state' => 'paid', 'date' => now()]);

    Livewire::actingAs($user)
        ->test(Filters::class)
        ->call('delete', $transaction->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
});

test('other user cannot delete transaction from filters', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($owner)
        ->for(Category::factory()->for($owner))
        ->create(['state' => 'paid', 'date' => now()]);

    Livewire::actingAs($attacker)
        ->test(Filters::class)
        ->call('delete', $transaction->id);

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
});

// --- PendingTransactions authorization ---

test('owner can mark pending transaction as paid', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->pending()
        ->create();

    Livewire::actingAs($user)
        ->test(PendingTransactions::class)
        ->call('changeStatus', $transaction->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'state' => 'paid']);
});

test('other user cannot mark pending transaction as paid', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($owner)
        ->for(Category::factory()->for($owner))
        ->pending()
        ->create();

    Livewire::actingAs($attacker)
        ->test(PendingTransactions::class)
        ->call('changeStatus', $transaction->id);

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'state' => 'pending']);
});

test('other user cannot delete pending transaction', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($owner)
        ->for(Category::factory()->for($owner))
        ->pending()
        ->create();

    Livewire::actingAs($attacker)
        ->test(PendingTransactions::class)
        ->call('delete', $transaction->id);

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
});

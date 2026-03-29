<?php

use App\Livewire\Transactions\Create;
use App\Livewire\Transactions\Update;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user cannot create a transaction assigned to another user category', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $otherCategory = Category::factory()->for($owner)->create();

    Livewire::actingAs($attacker)
        ->test(Create::class)
        ->set('form.type', 'expense')
        ->set('form.amount', 100)
        ->set('form.description', 'Test description')
        ->set('form.date', now()->format('Y-m-d'))
        ->set('form.category_id', $otherCategory->id)
        ->call('save')
        ->assertHasErrors(['form.category_id']);

    $this->assertDatabaseMissing('transactions', [
        'user_id'     => $attacker->id,
        'category_id' => $otherCategory->id,
    ]);
});

test('user cannot update a transaction to use another user category', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $attackerCategory = Category::factory()->for($attacker)->create();
    $otherCategory = Category::factory()->for($owner)->create();
    $transaction = Transaction::factory()
        ->for($attacker)
        ->for($attackerCategory)
        ->create(['state' => 'paid', 'date' => now()]);

    Livewire::actingAs($attacker)
        ->test(Update::class, ['transaction' => $transaction])
        ->set('form.category_id', $otherCategory->id)
        ->call('edit')
        ->assertHasErrors(['form.category_id']);

    $this->assertDatabaseHas('transactions', [
        'id'          => $transaction->id,
        'category_id' => $attackerCategory->id,
    ]);
});

test('marking pending transaction as paid sets expected_payment_date to null', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()
        ->for($user)
        ->for(Category::factory()->for($user))
        ->pending()
        ->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Transactions\PendingTransactions::class)
        ->call('changeStatus', $transaction->id);

    $this->assertDatabaseHas('transactions', [
        'id'                    => $transaction->id,
        'state'                 => 'paid',
        'expected_payment_date' => null,
    ]);
});

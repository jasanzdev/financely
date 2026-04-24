<?php

use App\Enums\TransactionState;
use App\Enums\TransactionType;
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
        'user_id' => $attacker->id,
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
        'id' => $transaction->id,
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
        'id' => $transaction->id,
        'state' => 'paid',
        'expected_payment_date' => null,
    ]);
});

// --- Enum casts & helpers ---

test('transaction type and state are cast to enum instances on fetch', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    Transaction::create([
        'type' => TransactionType::Income,
        'state' => TransactionState::Paid,
        'amount' => 100,
        'description' => 'enum round-trip',
        'date' => now(),
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $fresh = Transaction::where('description', 'enum round-trip')->first();

    expect($fresh->type)->toBe(TransactionType::Income);
    expect($fresh->state)->toBe(TransactionState::Paid);
});

test('query by enum case returns the same rows as query by string value', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    Transaction::factory()->for($user)->for($category)->count(3)->create();
    Transaction::factory()->for($user)->for($category)->pending()->count(2)->create();

    $byEnum = Transaction::where('state', TransactionState::Pending)->count();
    $byString = Transaction::where('state', 'pending')->count();

    expect($byEnum)->toBe(2);
    expect($byString)->toBe($byEnum);
});

test('isIncome and isExpense helpers reflect the type enum', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    $income = Transaction::factory()->for($user)->for($category)->create(['type' => TransactionType::Income]);
    $expense = Transaction::factory()->for($user)->for($category)->create(['type' => TransactionType::Expense]);

    expect($income->isIncome())->toBeTrue();
    expect($income->isExpense())->toBeFalse();
    expect($expense->isExpense())->toBeTrue();
    expect($expense->isIncome())->toBeFalse();
});

test('isPaid and isPending helpers reflect the state enum', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    $paid = Transaction::factory()->for($user)->for($category)->create();
    $pending = Transaction::factory()->for($user)->for($category)->pending()->create();

    expect($paid->isPaid())->toBeTrue();
    expect($paid->isPending())->toBeFalse();
    expect($pending->isPending())->toBeTrue();
    expect($pending->isPaid())->toBeFalse();
});

test('invalid type value fails validation', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.type', 'transfer')
        ->set('form.amount', 100)
        ->set('form.description', 'Invalid type test')
        ->set('form.date', now()->format('Y-m-d'))
        ->set('form.category_id', $category->id)
        ->call('save')
        ->assertHasErrors(['form.type']);
});

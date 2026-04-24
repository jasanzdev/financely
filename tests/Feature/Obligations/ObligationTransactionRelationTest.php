<?php

use App\Models\Category;
use App\Models\Obligation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- Schema invariants for transactions.obligation_id ---
// These pin the migration contract: the column exists, is nullable,
// and its FK behaves as ON DELETE SET NULL (not CASCADE, not RESTRICT).

test('transactions table has nullable obligation_id column', function () {
    expect(Schema::hasColumn('transactions', 'obligation_id'))->toBeTrue();

    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    $transaction = Transaction::factory()
        ->for($user)
        ->for($category)
        ->create();

    // Historical / freshly-created transactions may have no obligation.
    expect($transaction->obligation_id)->toBeNull();
});

test('transaction can be created with an obligation_id and it persists', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $obligation = Obligation::factory()->for($user)->create();

    $transaction = Transaction::factory()
        ->for($user)
        ->for($category)
        ->create(['obligation_id' => $obligation->id]);

    $fresh = Transaction::find($transaction->id);

    expect($fresh->obligation_id)->toBe($obligation->id);
});

// --- ON DELETE SET NULL behavior ---
// When an obligation is deleted, its transactions must survive with
// obligation_id = NULL. Historical audit data is preserved.

test('deleting an obligation sets obligation_id to null on its transactions, not deletes them', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $obligation = Obligation::factory()->for($user)->create();

    $transaction = Transaction::factory()
        ->for($user)
        ->for($category)
        ->create(['obligation_id' => $obligation->id]);

    $obligation->delete();

    $fresh = Transaction::find($transaction->id);

    expect($fresh)->not->toBeNull();
    expect($fresh->obligation_id)->toBeNull();
});

// --- Bidirectional relationship ---

test('obligation has many transactions relationship works', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $obligation = Obligation::factory()->for($user)->create();

    Transaction::factory()->for($user)->for($category)->count(3)->create(['obligation_id' => $obligation->id]);
    Transaction::factory()->for($user)->for($category)->create(); // unrelated

    expect($obligation->transactions)->toHaveCount(3);
});

test('transaction belongs to obligation relationship works', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $obligation = Obligation::factory()->for($user)->create(['name' => 'Internet']);

    $transaction = Transaction::factory()
        ->for($user)
        ->for($category)
        ->create(['obligation_id' => $obligation->id]);

    expect($transaction->obligation)->not->toBeNull();
    expect($transaction->obligation->name)->toBe('Internet');
});

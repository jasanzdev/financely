<?php

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\QueryException;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- Invariant: DB-level foreign key integrity.
//     The PHP guard in CategoryIndex::delete() is a UX convenience; the real
//     protection against orphan transactions is the FK on transactions.category_id.
//     These tests pin the DB-level behavior so that a refactor removing the
//     guard, a migration regression to CASCADE, or a framework default change
//     is caught immediately. ---

test('database rejects deleting a category with a paid transaction referencing it', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    Transaction::factory()->for($user)->for($category)->create(['state' => 'paid']);

    expect(fn () => $category->delete())->toThrow(QueryException::class);

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('database rejects deleting a category with a pending transaction referencing it', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    Transaction::factory()->for($user)->for($category)->pending()->create();

    expect(fn () => $category->delete())->toThrow(QueryException::class);

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

// Soft delete is an application-level concept; the FK constraint still sees
// the row as present. This is intentional — if we ever want "soft-deleted
// transactions release their category FK", that needs a dedicated purge path.
test('database rejects deleting a category even when its transactions are soft-deleted', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $transaction = Transaction::factory()->for($user)->for($category)->create();

    $transaction->delete();
    $this->assertSoftDeleted('transactions', ['id' => $transaction->id]);

    expect(fn () => $category->delete())->toThrow(QueryException::class);

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('category with no transactions can be deleted directly via eloquent', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    $category->delete();

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

<?php

use App\Livewire\Transactions\Filters;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- Invariant: Filters::render() must not throw UnhandledMatchError
//     when selectedTab holds an unexpected value (client tampering,
//     regression of code, stale state). ---

test('unknown selectedTab value does not throw 500 in render', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Filters::class)
        ->set('selectedTab', 'unknown-tab-value')
        ->assertHasNoErrors()
        ->assertOk();
});

test('empty selectedTab value does not throw 500 in render', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Filters::class)
        ->set('selectedTab', '')
        ->assertHasNoErrors()
        ->assertOk();
});

// --- Invariant: fallback path must preserve tenant isolation.
//     A broken default branch could silently drop the user_id filter. ---

test('fallback on unknown selectedTab preserves tenant isolation', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryB = Category::factory()->for($userB)->create();

    Transaction::factory()
        ->for($userB)
        ->for($categoryB)
        ->create([
            'type' => 'income',
            'amount' => 9999,
            'state' => 'paid',
            'date' => now(),
            'description' => 'secret-of-b',
        ]);

    $component = Livewire::actingAs($userA)
        ->test(Filters::class)
        ->set('selectedTab', 'foo');

    expect((float) $component->viewData('incomes'))->toBe(0.0);
    expect((float) $component->viewData('expenses'))->toBe(0.0);
    $component->assertDontSee('secret-of-b');
});

// --- Invariant: fallback must not broaden scope beyond the most restrictive
//     legitimate tab (7 days). Returning all-history would leak older data
//     the user never requested. ---

test('fallback on unknown selectedTab does not broaden scope beyond 7 days', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    Transaction::factory()
        ->for($user)
        ->for($category)
        ->create([
            'type' => 'income',
            'amount' => 100,
            'state' => 'paid',
            'date' => now()->subDays(3),
            'description' => 'recent-tx',
        ]);

    Transaction::factory()
        ->for($user)
        ->for($category)
        ->create([
            'type' => 'income',
            'amount' => 500,
            'state' => 'paid',
            'date' => now()->subDays(30),
            'description' => 'old-tx-outside-window',
        ]);

    $component = Livewire::actingAs($user)
        ->test(Filters::class)
        ->set('selectedTab', 'unknown');

    expect((float) $component->viewData('incomes'))->toBe(100.0);
    $component->assertSee('recent-tx')
        ->assertDontSee('old-tx-outside-window');
});

// --- latestDays() tab wiring ---
// Protects the days-tab behavior from drift while the unused $days parameter
// is removed. The tab must continue to show only transactions within the last
// 7 days, end-to-end.

test('clicking the days tab filters the render to the last 7 days', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    Transaction::factory()
        ->for($user)
        ->for($category)
        ->create([
            'type' => 'income',
            'amount' => 100,
            'state' => 'paid',
            'date' => now()->subDays(3),
            'description' => 'within-7-days-window',
        ]);

    Transaction::factory()
        ->for($user)
        ->for($category)
        ->create([
            'type' => 'income',
            'amount' => 500,
            'state' => 'paid',
            'date' => now()->subDays(30),
            'description' => 'outside-7-days-window',
        ]);

    $component = Livewire::actingAs($user)
        ->test(Filters::class)
        ->set('selectedTab', 'historical')
        ->call('latestDays');

    expect($component->get('selectedTab'))->toBe('days');
    expect((float) $component->viewData('incomes'))->toBe(100.0);
    $component->assertSee('within-7-days-window')
        ->assertDontSee('outside-7-days-window');
});

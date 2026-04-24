<?php

use App\Livewire\Obligations\MonthlyBillings;
use App\Models\Category;
use App\Models\Obligation;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

test('deleting an obligation removes its pending transactions', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'category' => 'Obligaciones Mensuales',
        'slug' => 'obligaciones-mensuales',
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
    $this->assertSoftDeleted('transactions', [
        'user_id' => $user->id,
        'description' => 'Alquiler',
        'state' => 'pending',
        'category_id' => $category->id,
    ]);
});

test('deactivating an obligation removes its pending transactions', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'category' => 'Obligaciones Mensuales',
        'slug' => 'obligaciones-mensuales',
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
    $this->assertSoftDeleted('transactions', [
        'user_id' => $user->id,
        'description' => 'Seguro',
        'state' => 'pending',
    ]);
});

test('activating an obligation creates pending transactions', function () {
    $user = User::factory()->create();
    $obligation = Obligation::factory()->for($user)->inactive()->create([
        'name' => 'Internet',
        'amount' => 50,
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

// --- Temporal invariants of ObligationForm::createTransactions ---

// Invariant: month overflow. Carbon::today()->setMonth($m) on day 29-31 of
// today produces a date that spills into the next month (e.g. Jan 31 → Feb 31
// overflows to Mar 3). The fix builds dates with Carbon::create($year, $month, $day)
// instead of mutating today().

test('obligation created on day 31 generates transactions whose date stays in the correct month', function () {
    Carbon::setTestNow(Carbon::create(2026, 1, 31, 12));
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'AlquilerEneroFin')
        ->set('form.amount', 100)
        ->set('form.description', 'Pago de alquiler fin de enero')
        ->set('form.limit_day', 10)
        ->call('save')
        ->assertHasNoErrors();

    $transactions = Transaction::where('user_id', $user->id)
        ->where('description', 'AlquilerEneroFin')
        ->get();

    // Feb → month 2 (NOT 3 from overflow). Apr → 4 (NOT 5). Jun → 6. Sep → 9. Nov → 11.
    $months = $transactions->pluck('date')->map(fn ($d) => Carbon::parse($d)->month)->sort()->values()->all();
    expect($months)->toBe([2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
});

test('obligation created on day 30 of march does not overflow into may', function () {
    Carbon::setTestNow(Carbon::create(2026, 3, 30, 12));
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'MarzoFin')
        ->set('form.amount', 50)
        ->set('form.description', 'Pago creado en marzo 30')
        ->set('form.limit_day', 5)
        ->call('save')
        ->assertHasNoErrors();

    $transactions = Transaction::where('user_id', $user->id)
        ->where('description', 'MarzoFin')
        ->get();

    // day 30 >= limit 5 → starts next month (April). April has 30 days: setMonth(4)
    // on March 30 would produce April 30 (OK). But June has 30 days: setMonth(6)
    // on March 30 still OK. November too. The hidden trap is Feb — but we're
    // starting from April, so no Feb involvement. Still test that every month is
    // its own index.
    $months = $transactions->pluck('date')->map(fn ($d) => Carbon::parse($d)->month)->sort()->values()->all();
    expect($months)->toBe([4, 5, 6, 7, 8, 9, 10, 11, 12]);
});

// Invariant: when today is past limit_day in December, nothing is generated
// for the current cycle. This works today "by accident" (loop condition
// 13 <= 12 is false). The fix makes the early return explicit.

test('obligation created in december after limit_day generates no transactions for current cycle', function () {
    Carbon::setTestNow(Carbon::create(2026, 12, 15, 12));
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'DicTarde')
        ->set('form.amount', 100)
        ->set('form.description', 'Obligacion creada tarde en diciembre')
        ->set('form.limit_day', 10)
        ->call('save')
        ->assertHasNoErrors();

    $count = Transaction::where('user_id', $user->id)
        ->where('description', 'DicTarde')
        ->count();

    expect($count)->toBe(0);
});

test('obligation created in december before limit_day generates exactly one december transaction', function () {
    Carbon::setTestNow(Carbon::create(2026, 12, 5, 12));
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'DicTemprano')
        ->set('form.amount', 100)
        ->set('form.description', 'Obligacion creada temprano en diciembre')
        ->set('form.limit_day', 10)
        ->call('save')
        ->assertHasNoErrors();

    $transactions = Transaction::where('user_id', $user->id)
        ->where('description', 'DicTemprano')
        ->get();

    expect($transactions)->toHaveCount(1);
    expect(Carbon::parse($transactions->first()->date)->month)->toBe(12);
    expect(Carbon::parse($transactions->first()->date)->year)->toBe(2026);
});

test('obligation created in january before limit_day generates twelve transactions', function () {
    Carbon::setTestNow(Carbon::create(2026, 1, 5, 12));
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'EneroTemprano')
        ->set('form.amount', 100)
        ->set('form.description', 'Obligacion creada temprano en enero')
        ->set('form.limit_day', 10)
        ->call('save')
        ->assertHasNoErrors();

    $transactions = Transaction::where('user_id', $user->id)
        ->where('description', 'EneroTemprano')
        ->get();

    expect($transactions)->toHaveCount(12);

    $months = $transactions->pluck('date')->map(fn ($d) => Carbon::parse($d)->month)->sort()->values()->all();
    expect($months)->toBe([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
});

// Invariant: every generated transaction must belong to the current year.
// Guards against future refactors that could let setMonth spill into next year.

test('all generated transactions stay within the current year', function () {
    Carbon::setTestNow(Carbon::create(2026, 11, 5, 12));
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MonthlyBillings::class)
        ->set('form.name', 'NovCheck')
        ->set('form.amount', 100)
        ->set('form.description', 'Verificacion de año actual')
        ->set('form.limit_day', 10)
        ->call('save')
        ->assertHasNoErrors();

    $transactions = Transaction::where('user_id', $user->id)
        ->where('description', 'NovCheck')
        ->get();

    foreach ($transactions as $tx) {
        expect(Carbon::parse($tx->date)->year)->toBe(2026);
        expect(Carbon::parse($tx->expected_payment_date)->year)->toBe(2026);
    }
});

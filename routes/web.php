<?php

use App\Livewire\Category\CategoryEdit;
use App\Livewire\Category\CategoryIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Transactions\Filters;
use App\Livewire\Transactions\PendingTransactions;
use App\Livewire\Transactions\Update;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('transaction/{transaction}/edit', Update::class)->name('transaction.edit');
    Route::get('transaction', Filters::class)->name('transaction.filters');

    Route::get('category', CategoryIndex::class)->name('category.index');
    Route::get('category/{category}/edit', CategoryEdit::class)->name('category.edit');

    Route::get('pending-transactions', PendingTransactions::class)->name('pending.transactions');
});

require __DIR__ . '/auth.php';

<?php

namespace App\Models;

use App\Enums\TransactionState;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ['type', 'amount', 'description', 'state', 'expected_payment_date', 'date', 'user_id', 'category_id', 'obligation_id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'expected_payment_date' => 'date',
        'type' => TransactionType::class,
        'state' => TransactionState::class,
    ];

    public function isIncome(): bool
    {
        return $this->type === TransactionType::Income;
    }

    public function isExpense(): bool
    {
        return $this->type === TransactionType::Expense;
    }

    public function isPaid(): bool
    {
        return $this->state === TransactionState::Paid;
    }

    public function isPending(): bool
    {
        return $this->state === TransactionState::Pending;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function obligation(): BelongsTo
    {
        return $this->belongsTo(Obligation::class);
    }

    protected function getFormattedDateAttribute()
    {
        return $this->date?->format('Y-m-d') ?? '';
    }

    public function getFormattedExpectedPaymentDateAttribute()
    {
        return $this->expected_payment_date?->format('Y-m-d') ?? '';
    }

    public function getIsPaymentFutureAttribute()
    {
        if (! $this->expected_payment_date) {
            return null;
        }

        return $this->expected_payment_date >= now()->startOfDay();
    }
}

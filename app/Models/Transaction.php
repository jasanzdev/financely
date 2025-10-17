<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasUuids;

    protected $fillable = ['type', 'amount', 'description', 'state', 'expected_payment_date', 'date', 'user_id', 'category_id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'expected_payment_date' => 'date',
        'state' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
        return $this->expected_payment_date >= now()->startOfDay() ?? true;
    }
}

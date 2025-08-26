<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['type', 'amount', 'description', 'state', 'expected_payment_date', 'date', 'user_id', 'category_id'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('y-m-d'),
        );
    }

    protected function expectedPaymentDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('y-m-d'),
        );
    }
}

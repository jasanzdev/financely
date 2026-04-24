<?php

namespace App\Enums;

enum TransactionState: string
{
    case Paid = 'paid';
    case Pending = 'pending';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_number',
        'amount',
        'currency',
        'provider',
        'status',
        'transaction_id',
        'response_code',
    ];
}

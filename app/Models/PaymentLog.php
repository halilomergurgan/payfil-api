<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'status', 'logged_at'];

    protected $casts = [
        'logged_at' => 'datetime',
    ];
}

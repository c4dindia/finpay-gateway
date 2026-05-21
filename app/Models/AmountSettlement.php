<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmountSettlement extends Model
{
    use HasFactory;

    protected $table = 'amount_settlements';

    protected $fillable = [
        'accountId',
        'currency',
        'amount',
        'commission',
        'checkout_id',
        'description',
        'payment_service',
        'status',
    ];
}

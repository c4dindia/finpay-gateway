<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutDetail extends Model
{
    use HasFactory;

    protected $table = 'checkout_details';

    protected $fillable = [
        'accId',
        'payment_partner',
        'amount',
        'currency',
        'amount_from',
        'currency_from',
        'email',
        'checkout_id',
        'checkout_integrity',
        'status',
    ];
}

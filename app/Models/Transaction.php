<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'account_id',
        'currency',
        'amount',
        'from_currency',
        'from_amount',
        'net_amount',
        'fees',
        'checkout_id',
        'payment_id',
        'payment_status',
        'description',
        'customer_details',
        'payer_details',
        'transvoucher_blockchainHashTrxn',
        'transvoucher_card_brand',
        'card_number',
        'status',
        'token',
    ];

    protected $casts = [
        'payer_details' => 'array',
    ];
}

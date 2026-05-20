<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PEightPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'luqapay';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'api_key',
        'b_token',
        'luqapay_apikey',
        'luqapay_secretkey',
        'luqapay_mid',
        'luqapay_subscription_apikey',
        'luqapay_subscription_secretkey',
        'luqapay_subscription_mid',
        // 'password',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

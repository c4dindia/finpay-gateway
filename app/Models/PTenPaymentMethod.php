<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTenPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'inabit';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',

        'inabit_widget_type',

        'inabit_widget_id',
        'inabit_widget_balance',
        'inabit_widget_api_key',
        'inabit_merchant_name',

        'inabit_purchase_widget_id',
        'inabit_purchase_widget_balance',
        'inabit_purchase_widget_api_key',
        'inabit_purchase_merchant_name',

        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

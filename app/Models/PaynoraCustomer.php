<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaynoraCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'accountId',
        'provider_customer_id',
        'merchant_customer_id',
        'first_name',
        'last_name',
        'customer_email',
        'country_code',
        'birth_date',
        'phone_no',
        'address1',
        'city',
        'state',
        'zip_code',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

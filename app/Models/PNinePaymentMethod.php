<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PNinePaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'trigopayments';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'trigo_personal_hash',
        'trigo_company_number',

        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSixPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'transvoucher';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'api_key',
        'b_token',
        'status',
        'salesChannel',
        'apiKey',
        'secretKey',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

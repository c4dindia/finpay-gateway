<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrustitBanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'merchant_public_key',
        'merchant_secret_key',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

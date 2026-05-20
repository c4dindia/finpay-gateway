<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UPIPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'mid',
        'vpa',
        'midv2',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

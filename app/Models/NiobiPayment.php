<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiobiPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'clientId',
        'clientSecret',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

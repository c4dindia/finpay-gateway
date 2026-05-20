<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytoroPayment extends Model
{
    use HasFactory;
    protected $fillable =[
        'company_id',
        'accountId',
        'redirect_url',
        'auth_key',
        'secret_key',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtomPay extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'auth_id',
        'auth_key',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

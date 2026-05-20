<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSevenPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'securepayzone';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'api_key',
        'b_token',
        'spz_authkey',
        'spz_secretkey',
        'username',
        'password',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

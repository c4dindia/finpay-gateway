<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTwelvePaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'pgtechpay';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'token',
        'login_email',
        'login_pass',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

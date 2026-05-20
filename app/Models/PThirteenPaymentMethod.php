<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PThirteenPaymentMethod extends Model
{
    use HasFactory;

    protected $table = "aliz7";

    protected $fillable =[
        'company_id',
        'accountId',
        'redirect_url',
        'aliz7_token',
        'aliz7_secret',
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

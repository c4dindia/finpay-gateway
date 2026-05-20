<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PFourPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'stradapay';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'mid_code',
        'mid_secret',
        'payby',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

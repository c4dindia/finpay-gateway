<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PEighteenPaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'keynexpay';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'keynexpay_api_key',
        'keynexpay_secret_key',
        'api_key',
        'b_token',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

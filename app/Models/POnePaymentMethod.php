<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POnePaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'paystrax';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

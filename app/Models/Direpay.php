<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direpay extends Model
{
    use HasFactory;
    protected $table = 'direpay';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'direpay_api_email',
        'direpay_api_password',
        'direpay_api_secret',
        'api_key',
        'b_token',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

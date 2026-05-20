<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValensPay extends Model
{
    protected $table = 'valenspay';

    use HasFactory;
    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'valenspay_client_key',
        'valenspay_secret',
        'api_key',
        'b_token',
        'status',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

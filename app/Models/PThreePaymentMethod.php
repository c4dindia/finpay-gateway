<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PThreePaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'xOne';

    protected $fillable = [
        'company_id',
        'accountId',
        'redirect_url',
        'api_key',
        'b_token',
        'status',
        'widget_id',
        'script_url',
        'widget_secret_key',
        'data_address',
        'success_url',
        'error_url',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}

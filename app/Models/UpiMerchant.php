<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpiMerchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'mid',
        'vpa',
        'limitPerDay',
        'limitPerMonth',
        'limitPerYear',
        'status'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X1Token extends Model
{
    use HasFactory;

    protected $table = 'x1_tokens';

    protected $fillable = [
        'email',
        'token',
        'widget_id',
    ];
}

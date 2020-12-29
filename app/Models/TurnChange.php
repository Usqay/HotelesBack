<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TurnChange extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'turn_id',
        'open_by_user_id',
        'close_by_user_id',
        'currency_base_id',
        'open_date',
        'close_date',
        'status_active',
    ];
}

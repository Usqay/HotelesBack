<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'open_time',
        'close_time',
    ];
}

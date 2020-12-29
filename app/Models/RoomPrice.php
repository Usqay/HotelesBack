<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_id',
        'currency_id',
        'day_price',
        'hour_price',
    ];

    
    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }
}

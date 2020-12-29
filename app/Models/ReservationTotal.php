<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationTotal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reservation_id',
        'currency_id',
        'total',
        'total_by',
        'discount',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }
}
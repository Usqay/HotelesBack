<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_id',
        'currency_id',
        'sale_price',
        'is_base'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }
}

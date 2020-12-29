<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'service_id',
        'currency_id',
        'unit_price',
        'quantity',
        'rate_value'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }
}

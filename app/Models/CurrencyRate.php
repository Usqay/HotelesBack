<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rate_date',
        'currency_id',
        'rate_value'
    ];
    
    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }
}

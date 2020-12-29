<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_id',
        'product_id',
        'quantity',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}

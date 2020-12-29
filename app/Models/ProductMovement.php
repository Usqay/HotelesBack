<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'store_house_movement_id',
        'product_movement_type_id',
        'quantity',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
    
    public function product_movement_type()
    {
        return $this->belongsTo(ProductMovementType::class)->withTrashed();
    }
}

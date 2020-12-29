<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomProduct extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'room_id',
        'product_id',
        'quantity',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}

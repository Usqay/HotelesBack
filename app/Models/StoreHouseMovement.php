<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreHouseMovement extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'store_house_id',
        'store_house_movement_type_id',
        'description',
    ];
    
    public function products()
    {        
        return $this->hasMany(ProductMovement::class)->withTrashed();
    }

    public function store_house_movement_type()
    {
        return $this->belongsTo(StoreHouseMovementType::class)->withTrashed();
    }
}

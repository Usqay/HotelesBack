<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'room_category_id',
        'room_status_id',
    ];

    public function room_category()
    {
        return $this->belongsTo(RoomCategory::class)->withTrashed();
    }
    
    public function room_status()
    {
        return $this->belongsTo(RoomStatus::class)->withTrashed();
    }
    
    public function room_prices()
    {        
        return $this->hasMany(RoomPrice::class);
    }
    
    public function room_products()
    {        
        return $this->hasMany(RoomProduct::class);
    }
}

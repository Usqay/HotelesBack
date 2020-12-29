<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'token_for_observer',
        'client_id',
        'coupon_id',
        'sale_state_id',
        'turn_change_id',
        'reservation_id',
        'store_house_movement_id',
        'room_id',
    ];
    
    public function totals(){
        return $this->hasMany(SaleTotal::class);
    }
    
    public function products(){
        return $this->hasMany(SaleProduct::class);
    }
    
    public function services(){
        return $this->hasMany(SaleService::class);
    }
    
    public function sale_state()
    {
        return $this->belongsTo(SaleState::class)->withTrashed();
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }
    
    public function room()
    {
        return $this->belongsTo(Room::class)->withTrashed();
    }
}

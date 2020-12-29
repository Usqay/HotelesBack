<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'start_date',
        'end_date',
        'total_days',
        'total_hours',
        'description',
        'client_id',
        'reservation_origin_id',
        'coupon_id',
        'reservation_state_id',
        'turn_change_id',
        'token_for_observer'
    ];

    public function rooms(){
        return $this->hasMany(ReservationRoom::class);
    }

    public function totals(){
        return $this->hasMany(ReservationTotal::class);
    }

    public function guests(){
        return $this->hasMany(ReservationGuest::class);
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function reservation_origin()
    {
        return $this->belongsTo(ReservationOrigin::class)->withTrashed();
    }

    public function reservation_state()
    {
        return $this->belongsTo(ReservationState::class)->withTrashed();
    }

}

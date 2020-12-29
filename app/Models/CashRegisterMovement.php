<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashRegisterMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'currency_id',
        'cash_register_movement_type_id',
        'cash_register_id',
        'turn_change_id',
        'payment_method_id',
        'user_id',
        'amount',
        'description',
        'additional_info',
    ];
    
    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    public function payment_mehod()
    {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }
    
    public function cash_register_movement_type()
    {
        return $this->belongsTo(CashRegisterMovementType::class)->withTrashed();
    }
    
    public function cash_register()
    {
        return $this->belongsTo(CashRegister::class)->withTrashed();
    }
}

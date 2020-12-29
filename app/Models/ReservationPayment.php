<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationPayment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'description',
        'reservation_id',
        'currency_id',
        'payment_method_id',
        'cash_register_movement_id',
        'electronic_voucher_id',
        'people_id',
        'total',
        'payment_by',
        'print_payment',
        'document_type'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'sale_id',
        'currency_id',
        'cash_register_movement_id',
        'electronic_voucher_id',
        'payment_method_id',
        'people_id',
        'total',
        'print_payment',
        'document_type',
    ];
}

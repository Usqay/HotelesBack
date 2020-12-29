<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectronicVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date_emitted',
        'electronic_voucher_type_id',
        'number',
        'serie',
        'print',
        'api_body',
        'api_response',
        'api_state',
        'adittional_info',
    ];
    
    public function electronic_voucher_type()
    {
        return $this->belongsTo(ElectronicVoucherType::class)->withTrashed();
    }
}

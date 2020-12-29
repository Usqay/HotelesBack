<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Printer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'printer_type_id',
        'name',
        'port',
        'ip_address'
    ];
    
    public function printer_type()
    {
        return $this->belongsTo(PrinterType::class)->withTrashed();
    }
    
}

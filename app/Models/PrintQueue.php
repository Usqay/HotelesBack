<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintQueue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'show_logo',
        'show_business_info',
        'show_people_info',
        'show_items',
        'people_id',
        'additional_header_info',
        'items_headers',
        'items_values',
        'qr_code',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'sunat_code'
    ];

    public function prices(){
        return $this->hasMany(ServicePrice::class);
    }

    public function products(){
        return $this->hasMany(ServiceProduct::class);
    }
}

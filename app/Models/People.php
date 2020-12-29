<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class People extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
        'full_name',
        'gender_id',
        'document_type_id',
        'document_number',
        'address',
        'phone_number',
        'email',
        'birthday_date',
    ];
    
    public function gender()
    {
        return $this->belongsTo(Gender::class)->withTrashed();
    }
    
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class)->withTrashed();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'responsible_name',
        'responsible_phone',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];
    
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
    public function deletedBy(){
        return $this->belongsTo(User::class, 'deleted_by')->withDefault();
    }
}

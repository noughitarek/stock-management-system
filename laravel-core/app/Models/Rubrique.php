<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubrique extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
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
    public function products(){
        return $this->hasMany(Product::class)
            ->with(['createdBy', 'updatedBy', 'deletedBy'])
            ->whereNull('deleted_at')
            ->whereNull('deleted_by')
            ->orderBy('id', 'desc');
    }

}

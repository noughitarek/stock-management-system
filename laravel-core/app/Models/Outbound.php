<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    use HasFactory;
    protected $fillable = [
        'outbound_at',
        'rubrique_id',
        'internal_delivery_note_number',
        'service_id',
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
    public function rubrique(){
        return $this->belongsTo(Rubrique::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function outboundProducts(){
        return $this->hasMany(OutboundProduct::class);
    }
}

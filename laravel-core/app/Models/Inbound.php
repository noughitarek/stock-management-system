<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    use HasFactory;

    protected $fillable = [
        'inbound_at',
        'rubrique_id',
        'commande_note_number',
        'delivery_note_number',
        'invoice_number',
        'supplier_id',
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
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function inboundProducts(){
        return $this->hasMany(InboundProduct::class);
    }
}

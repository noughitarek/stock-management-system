<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'rubrique_id',
        'description',
        'init_stock',
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
    public function total_inbounds()
    {
        return InboundProduct::where('product_id', $this->id)->sum('qte');
    }
    public function total_outbounds()
    {
        return OutboundProduct::where('product_id', $this->id)->sum('qte');
    }
    public function stock()
    {
        return $this->init_stock+$this->total_inbounds()-$this->total_outbounds();
    }
}

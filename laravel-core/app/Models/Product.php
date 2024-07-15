<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['designation', 'rubrique', 'description', 'pictures', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by')->withDefault();
    }
    public function rubrique()
    {
        return $this->belongsTo(Rubrique::class, 'rubrique')->withDefault();
    }
    public function inboundProducts()
    {
        return $this->hasMany(InboundProduct::class, 'product');
    }
    public function outboundProducts()
    {
        return $this->hasMany(OutboundProduct::class, 'product');
    }
    public function unit_price_excl_tax()
    {
        return 0;
    }
    public function unit_price_net()
    {
        return 0;
    }
    public function total_amount_excl_tax()
    {
        return 0;
    }
    public function total_amount_net()
    {
        return 0;
    }
    public function inbounds()
    {
        $qte = 0;
        foreach($this->inboundProducts as $inProduct)
        {
            $qte += $inProduct->qte;
        }
        return $qte;
    }
    public function outbounds()
    {
        $qte = 0;
        foreach($this->outboundProducts as $outProduct)
        {
            $qte += $outProduct->qte;
        }
        return $qte;
    }
    public function stock()
    {
        return $this->inbounds()-$this->outbounds();
    }
}

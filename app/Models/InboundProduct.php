<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundProduct extends Model
{
    use HasFactory;
    protected $fillable = ["product", "unit_price_excl_tax", "unit_price_net", "qte", "total_amount_excl_tax", "total_amount_net", "inbound"];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product');
    }
    public function inbound()
    {
        return $this->belongsTo(Inbound::class, 'inbound');
    }
}

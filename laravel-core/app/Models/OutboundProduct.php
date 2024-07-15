<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutboundProduct extends Model
{
    use HasFactory;
    protected $fillable = ["product", "unit_price_excl_tax", "unit_price_net", "qte", "total_amount_excl_tax", "total_amount_net", "outbound"];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product');
    }
    public function outbound()
    {
        return $this->belongsTo(Outbound::class, 'outbound');
    }
}

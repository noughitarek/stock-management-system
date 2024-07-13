<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundProduct extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product');
    }
    public function inbound()
    {
        return $this->belongsTo(Inbound::class, 'inbound');
    }
}

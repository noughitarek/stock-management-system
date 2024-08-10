<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutboundProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'unit_price_excl_tax',
        'unit_price_net',
        'qte',
        'total_amount_excl_tax',
        'total_amount_net',
        'outbound_id',
    ];
    public function product(){
        return $this->belongsTo(Product::class)
            ->with(['createdBy', 'updatedBy', 'deletedBy'])
            ->whereNull('deleted_at')
            ->whereNull('deleted_by')
            ->orderBy('id', 'desc');
    }
    public function outbound(){
        return $this->belongsTo(Outbound::class);
    }
}

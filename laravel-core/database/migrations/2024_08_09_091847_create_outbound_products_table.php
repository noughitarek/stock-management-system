<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('outbound_products', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('product_id')->constrained('products');

            $table->double('unit_price_excl_tax');
            $table->double('unit_price_net');
            $table->integer('qte');
            $table->double('total_amount_excl_tax');
            $table->double('total_amount_net');

            $table->foreignId('outbound_id')->constrained('outbounds')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_products');
    }
};

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
        Schema::create('inbounds', function (Blueprint $table) {
            $table->id();
            
            $table->timestamp('inbound_at');

            $table->foreignId('rubrique_id')->constrained('rubriques');
            $table->string('commande_note_number')->index()->nullable();
            $table->string('delivery_note_number')->index()->nullable();
            $table->string('invoice_number')->index()->nullable();

            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbounds');
    }
};

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
        Schema::create('outbound_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outbound_id')->constrained()->cascadeOnDelete();
            
            $table->string('variant_type')->nullable(); // 'single', 'double', 'capacity_based'
            $table->string('variant_label'); // 'Single (1 pax)', '1 Perahu 4 orang'
            
            // Untuk capacity-based pricing (rafting)
            $table->integer('min_pax_per_unit')->nullable();
            $table->integer('max_pax_per_unit')->nullable();
            
            // Special conditions
            $table->boolean('includes_documentation')->default(false);
            
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_variants');
    }
};

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
        Schema::create('outbounds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // 'per_pax' = per orang (Flying Fox, Paintball, Team Building, ATV)
            // 'per_unit' = per unit seperti mobil/perahu (Offroad, Rafting)
            $table->enum('pricing_type', ['per_pax', 'per_unit'])->default('per_pax');
            $table->string('unit_name')->nullable(); // perahu, mobil, etc
            
            // Participant limits
            $table->integer('min_participants')->default(1);
            $table->integer('max_participants')->nullable(); // null = unlimited
            $table->integer('min_age')->nullable();

            $table->integer('duration')->nullable(); // in minutes
            $table->decimal('distance', 8, 2)->nullable(); // in km
            
            // Special features flags
            $table->boolean('has_variants')->default(false); // for ATV (single/double)
            $table->boolean('allows_documentation_addon')->default(false); // for rafting
            
            // Transportaasi (Rp 200k/mobil kecuali Rafting & Offroad)
            $table->boolean('requires_transportation')->default(true); // false for Rafting & Offroad
            
            $table->boolean('has_camping_package')->default(false); // Kemah + Arung Jeram
            
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
        Schema::dropIfExists('outbounds');
    }
};

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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            // One of these must be filled: unit_id (for glamping), item_id (for additional items), or outbound_id (for outbound activities)
            $table->foreignId('unit_id')->nullable()->constrained('area_units')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('outbound_id')->nullable()->constrained('outbounds')->cascadeOnDelete();
            $table->foreignID('outbound_variant_id')->nullable()->constrained('outbound_variants')->cascadeOnDelete();
            $table->foreignId('season_id')->nullable()->constrained('season_dates')->cascadeOnDelete();
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};

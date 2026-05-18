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
        Schema::create('booking_outbounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('outbound_id')->constrained('outbounds')->cascadeOnDelete();
            $table->foreignId('outbound_variant_id')->nullable()->constrained('outbound_variants')->nullOnDelete();            
            // Schedule
            $table->date('schedule_date')->nullable();
            $table->time('schedule_time')->nullable();
            
            // Quantity based on pricing_type
            // If per_pax: total_participants
            // If per_unit: number_of_units (boats/cars) + participants_per_unit
            $table->integer('number_of_units')->default(1); // jumlah perahu/mobil
            $table->integer('participants_per_unit')->nullable(); // jumlah orang per perahu/mobil
            $table->integer('total_participants')->default(1); // total orang
            
            // Add-ons
            $table->boolean('add_documentation')->default(false);
            $table->integer('additional_documentation')->default(0); // jumlah dokumentasi tambahan
            $table->decimal('documentation_fee', 15, 2)->default(0);
            
            // Transportation (only if outbound.requires_transportation = true)
            $table->boolean('need_transportation')->default(false);
            $table->integer('transportation_vehicles')->default(0);
            $table->decimal('transportation_fee', 15, 2)->default(0); // Rp 200k/mobil, max 10 orang
            
            // Price breakdown
            $table->decimal('base_price', 15, 2)->default(0); // from prices table
            $table->decimal('subtotal', 15, 2)->default(0); // base_price * quantity
            $table->decimal('total_price', 15, 2)->default(0); // subtotal + fees
            
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_outbounds');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds indexes to optimize query performance for:
     * - Booking status filtering
     * - Booking detail date range queries (availability checks)
     * - Item type filtering
     * - Price lookups by unit and season
     */
    public function up(): void
    {
        // Index for filtering bookings by status
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('status', 'idx_bookings_status');
        });

        // Composite index for availability checks (most critical for performance)
        Schema::table('booking_details', function (Blueprint $table) {
            $table->index(['unit_id', 'check_in', 'check_out'], 'idx_booking_details_availability');
        });

        // Index for item type filtering in amenities
        Schema::table('items', function (Blueprint $table) {
            $table->index('type', 'idx_items_type');
        });

        // Composite index for price lookups
        Schema::table('prices', function (Blueprint $table) {
            $table->index(['unit_id', 'season_id'], 'idx_prices_unit_season');
        });

        // Index for season date range queries
        Schema::table('season_dates', function (Blueprint $table) {
            $table->index(['season_type', 'start_date', 'end_date'], 'idx_season_dates_type_range');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_status');
        });

        Schema::table('booking_details', function (Blueprint $table) {
            $table->dropIndex('idx_booking_details_availability');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('idx_items_type');
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->dropIndex('idx_prices_unit_season');
        });

        Schema::table('season_dates', function (Blueprint $table) {
            $table->dropIndex('idx_season_dates_type_range');
        });
    }
};

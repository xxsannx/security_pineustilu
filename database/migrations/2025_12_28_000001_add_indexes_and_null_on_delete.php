<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to common filter columns
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'status')) {
                $table->index('status');
            }
            if (Schema::hasColumn('bookings', 'booking_date')) {
                $table->index('booking_date');
            }
        });

        Schema::table('booking_details', function (Blueprint $table) {
            if (Schema::hasColumn('booking_details', 'check_in')) {
                $table->index('check_in');
            }
            if (Schema::hasColumn('booking_details', 'check_out')) {
                $table->index('check_out');
            }
            // composite index for searches by booking + date
            if (Schema::hasColumn('booking_details', 'booking_id') && Schema::hasColumn('booking_details', 'check_in')) {
                $table->index(['booking_id', 'check_in']);
            }
        });

        Schema::table('prices', function (Blueprint $table) {
            if (Schema::hasColumn('prices', 'season_id')) {
                $table->index('season_id');
            }

            // Attempt to change FK behavior for item_id and unit_id to nullOnDelete
            try {
                $table->dropForeign(['item_id']);
            } catch (\Exception $e) {
                // ignore if constraint doesn't exist
            }
            try {
                $table->dropForeign(['unit_id']);
            } catch (\Exception $e) {
                // ignore
            }

            // Re-add as nullOnDelete to preserve historical data when catalogue entries are removed
            if (Schema::hasColumn('prices', 'item_id')) {
                $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
            }
            if (Schema::hasColumn('prices', 'unit_id')) {
                $table->foreign('unit_id')->references('id')->on('area_units')->nullOnDelete();
            }
        });

        // booking_details.item_id -> nullOnDelete
        Schema::table('booking_details', function (Blueprint $table) {
            try {
                $table->dropForeign(['item_id']);
            } catch (\Exception $e) {
                // ignore
            }
            if (Schema::hasColumn('booking_details', 'item_id')) {
                $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'status')) {
                $table->dropIndex(['status']);
            }
            if (Schema::hasColumn('bookings', 'booking_date')) {
                $table->dropIndex(['booking_date']);
            }
        });

        Schema::table('booking_details', function (Blueprint $table) {
            if (Schema::hasColumn('booking_details', 'check_in')) {
                $table->dropIndex(['check_in']);
            }
            if (Schema::hasColumn('booking_details', 'check_out')) {
                $table->dropIndex(['check_out']);
            }
            if (Schema::hasColumn('booking_details', 'booking_id') && Schema::hasColumn('booking_details', 'check_in')) {
                $table->dropIndex(['booking_id', 'check_in']);
            }

            // We won't attempt to restore previous FK cascade behavior automatically
            try {
                $table->dropForeign(['item_id']);
            } catch (\Exception $e) {
            }
        });

        Schema::table('prices', function (Blueprint $table) {
            if (Schema::hasColumn('prices', 'season_id')) {
                $table->dropIndex(['season_id']);
            }
            try {
                $table->dropForeign(['item_id']);
            } catch (\Exception $e) {
            }
            try {
                $table->dropForeign(['unit_id']);
            } catch (\Exception $e) {
            }
        });
    }
};

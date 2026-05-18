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
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->default(0)->after('type');
        });

        // Migrate existing prices from prices table to items table
        // Get the first/default price for each item
        $items = DB::table('items')->get();
        foreach ($items as $item) {
            $price = DB::table('prices')
                ->where('item_id', $item->id)
                ->first();
            
            if ($price) {
                DB::table('items')
                    ->where('id', $item->id)
                    ->update(['price' => $price->price]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};

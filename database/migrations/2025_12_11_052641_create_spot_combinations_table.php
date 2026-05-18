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
        Schema::create('spot_combinations', function (Blueprint $table) {
            $table->id();
            $table->string('combination_code', 16); // contoh: 01001110
            $table->json('spots'); // menyimpan array ["spot1"=>0,"spot2"=>1,...]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spot_combinations');
    }
};

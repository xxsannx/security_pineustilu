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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('outbound_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); //Pribadi, umum dan outbound
            $table->string('icon')->nullable(); // icon for the facility
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};

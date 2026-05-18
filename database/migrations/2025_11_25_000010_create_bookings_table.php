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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('booking_type'); // glamping, outbound
            $table->date('booking_date');
            $table->string('token_code')->unique();
            $table->string('status')->default('proses'); // proses, pembayaran, berhasil, berjalan, selesai
            $table->string('guest_name');
            $table->string('guest_phone');
            $table->string('guest_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

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
        Schema::table('video_calls', function (Blueprint $table) {
            $table->uuid('room_id')->nullable();
            $table->text('doctor_token')->nullable();
            $table->text('patient_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_calls', function (Blueprint $table) {
            $table->dropColumn([
                'room_id',
                'doctor_token',
                'patient_token',
            ]);
        });
    }
};

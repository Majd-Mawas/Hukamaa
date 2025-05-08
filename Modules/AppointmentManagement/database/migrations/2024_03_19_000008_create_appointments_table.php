<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->string('time_slot');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->boolean('confirmed_by_doctor')->default(false);
            $table->boolean('confirmed_by_patient')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index(['doctor_id', 'status']);
            $table->index(['date', 'time_slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

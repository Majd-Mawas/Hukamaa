<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('age');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birth_date');
            $table->json('medical_history')->nullable();
            $table->json('chronic_conditions')->nullable();
            $table->json('allergies')->nullable();
            $table->json('current_medications')->nullable();
            $table->boolean('is_profile_complete')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_profiles');
    }
};

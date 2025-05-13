<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('phone_number');
            $table->text('address');
            $table->string('profile_picture')->nullable();
            $table->foreignId('specialization_id')->constrained();
            $table->string('title');
            $table->unsignedTinyInteger('experience_years');
            $table->text('experience_description');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('services')->comment('Array of services: remote_video_consultation, home_visit');
            $table->string('coverage_area')->nullable()->comment('Required if home_visit service is selected');
            $table->string('expertise_focus')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('specialization_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('department_name');
            $table->string('specialization_name');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['department_name', 'specialization_name']);
            $table->index('department_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
};

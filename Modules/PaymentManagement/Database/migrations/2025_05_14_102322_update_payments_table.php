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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('appointment_id')->after('doctor_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('payment_reference')->after('amount')->nullable()->unique();

            $table->index('appointment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn(['appointment_id', 'payment_reference']);
        });
    }
};

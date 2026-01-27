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
        Schema::create('rejection_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // 'mechanic' or 'insurance'
            $table->string('name');
            $table->string('email');
            $table->text('rejection_reason');
            $table->json('additional_data')->nullable(); // Store extra details
            $table->unsignedBigInteger('rejected_by'); // Admin user ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejection_logs');
    }
};

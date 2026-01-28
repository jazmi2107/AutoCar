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
        Schema::create('mechanic_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->unique();
            $table->string('license_number')->nullable();
            $table->string('specialization')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->enum('availability_status', ['available', 'busy', 'offline'])->default('available');
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->text('certifications')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mechanic_information');
    }
};

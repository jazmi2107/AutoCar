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
        Schema::dropIfExists('admin_information');
        Schema::dropIfExists('mechanic_information');
        Schema::dropIfExists('user_information');
        Schema::dropIfExists('mechanics'); // Drop old mechanics table
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate tables if needed for rollback
        Schema::create('admin_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->unique();
            $table->string('employee_id')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

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

        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->unique();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('membership_type')->default('basic');
            $table->string('insurance_provider')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

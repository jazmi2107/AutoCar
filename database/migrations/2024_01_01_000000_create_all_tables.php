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
        // Users Table
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('user'); // user, admin, mechanic, insurance_company
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('status')->default('active');
                $table->string('profile_image')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Password Reset Tokens
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Failed Jobs
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // Personal Access Tokens
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // Insurance Companies
        if (!Schema::hasTable('insurance_companies')) {
            Schema::create('insurance_companies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('company_name');
                $table->string('registration_number')->nullable();
                $table->string('phone_number')->nullable();
                $table->text('address')->nullable();
                $table->string('website')->nullable();
                $table->string('approval_status')->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->string('profile_picture')->nullable();
                $table->timestamps();
            });
        }

        // Mechanics
        if (!Schema::hasTable('mechanics')) {
            Schema::create('mechanics', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('phone_number')->nullable();
                $table->text('address')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('license_number')->nullable();
                $table->integer('years_of_experience')->nullable();
                $table->foreignId('insurance_company_id')->nullable()->constrained('insurance_companies')->onDelete('set null');
                $table->string('insurance_name')->nullable();
                $table->string('availability_status')->default('available');
                $table->string('approval_status')->default('pending');
                $table->decimal('rating', 3, 2)->default(0);
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('profile_picture')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }

        // Admins
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('phone_number')->nullable();
                $table->text('address')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('profile_picture')->nullable();
                $table->timestamps();
            });
        }

        // Drivers
        if (!Schema::hasTable('drivers')) {
            Schema::create('drivers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('phone_number')->nullable();
                $table->text('address')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('vehicle_make')->nullable();
                $table->string('vehicle_model')->nullable();
                $table->string('plate_number')->nullable();
                $table->string('profile_picture')->nullable();
                $table->timestamps();
            });
        }

        // Assistance Requests
        if (!Schema::hasTable('assistance_requests')) {
            Schema::create('assistance_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('insurance_company_id')->nullable()->constrained('insurance_companies')->onDelete('set null');
                $table->foreignId('mechanic_id')->nullable()->constrained('mechanics')->onDelete('set null');
                $table->string('insurance_name')->nullable();
                $table->string('breakdown_type')->nullable();
                $table->string('name')->nullable(); // Contact name
                $table->string('phone_number')->nullable();
                $table->string('plate_number')->nullable();
                $table->string('vehicle_make')->nullable();
                $table->string('vehicle_model')->nullable();
                $table->text('location_address')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->decimal('estimated_cost', 10, 2)->nullable();
                $table->decimal('final_cost', 10, 2)->nullable();
                $table->string('payment_status')->default('pending');
                $table->decimal('distance_fee', 10, 2)->nullable();
                $table->decimal('night_surcharge', 10, 2)->nullable();
                $table->decimal('total_cost', 10, 2)->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->integer('mechanic_rating')->nullable();
                $table->text('mechanic_review')->nullable();
                $table->timestamp('rated_at')->nullable();
                $table->timestamps();
            });
        }

        // Service Prices
        if (!Schema::hasTable('service_prices')) {
            Schema::create('service_prices', function (Blueprint $table) {
                $table->id();
                $table->string('service_type');
                $table->decimal('base_price', 10, 2)->default(0);
                $table->decimal('price_per_km', 10, 2)->default(0);
                $table->decimal('midnight_surcharge_rate', 10, 2)->default(0);
                $table->decimal('fuel_adjustment_factor', 10, 2)->default(0);
                $table->decimal('last_fuel_price', 10, 2)->nullable();
                $table->timestamp('last_fuel_check')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_prices');
        Schema::dropIfExists('assistance_requests');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('mechanics');
        Schema::dropIfExists('insurance_companies');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

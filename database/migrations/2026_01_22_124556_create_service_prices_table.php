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
        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();
            $table->string('service_type')->unique(); // Engine Problem, Battery, etc
            $table->decimal('base_price', 10, 2); // Base price for service
            $table->decimal('price_per_km', 10, 2)->default(3.00); // RM per km
            $table->decimal('midnight_surcharge_rate', 5, 2)->default(30.00); // 30% surcharge
            $table->decimal('fuel_adjustment_factor', 5, 2)->default(0.00); // % adjustment based on fuel
            $table->decimal('last_fuel_price', 10, 2)->nullable(); // Last recorded RON95 price
            $table->timestamp('last_fuel_check')->nullable(); // Last time fuel price was checked
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_prices');
    }
};

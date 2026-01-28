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
        Schema::table('assistance_requests', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('plate_number');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('location_address')->nullable()->after('longitude');
            $table->boolean('in_coverage')->default(true)->after('location_address');
            $table->boolean('extra_fee_applied')->default(false)->after('in_coverage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistance_requests', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'location_address', 'in_coverage', 'extra_fee_applied']);
        });
    }
};

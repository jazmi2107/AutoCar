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
            $table->decimal('base_price', 10, 2)->nullable()->after('status');
            $table->decimal('distance_fee', 10, 2)->nullable()->after('base_price');
            $table->decimal('night_surcharge', 10, 2)->nullable()->after('distance_fee');
            $table->decimal('total_cost', 10, 2)->nullable()->after('night_surcharge');
            $table->decimal('insurance_covered_amount', 10, 2)->nullable()->after('total_cost');
            $table->decimal('payable_amount', 10, 2)->nullable()->after('insurance_covered_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistance_requests', function (Blueprint $table) {
            $table->dropColumn([
                'base_price',
                'distance_fee',
                'night_surcharge',
                'total_cost',
                'insurance_covered_amount',
                'payable_amount'
            ]);
        });
    }
};

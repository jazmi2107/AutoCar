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
            $table->unsignedTinyInteger('mechanic_rating')->nullable()->after('completed_at');
            $table->text('mechanic_review')->nullable()->after('mechanic_rating');
            $table->timestamp('rated_at')->nullable()->after('mechanic_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistance_requests', function (Blueprint $table) {
            $table->dropColumn(['mechanic_rating', 'mechanic_review', 'rated_at']);
        });
    }
};

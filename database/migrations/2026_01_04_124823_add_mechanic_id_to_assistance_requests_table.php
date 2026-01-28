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
            $table->unsignedInteger('mechanic_id')->nullable()->after('user_id');
            $table->foreign('mechanic_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistance_requests', function (Blueprint $table) {
            $table->dropForeign(['mechanic_id']);
            $table->dropColumn('mechanic_id');
        });
    }
};

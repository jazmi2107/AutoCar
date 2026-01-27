<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing 'accepted' records to 'assigned'
        DB::table('assistance_requests')
            ->where('status', 'accepted')
            ->update(['status' => 'assigned']);

        // Then remove 'accepted' from the enum
        DB::statement("ALTER TABLE assistance_requests MODIFY COLUMN status ENUM('pending', 'assigned', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add 'accepted' back to the enum
        DB::statement("ALTER TABLE assistance_requests MODIFY COLUMN status ENUM('pending', 'assigned', 'accepted', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};

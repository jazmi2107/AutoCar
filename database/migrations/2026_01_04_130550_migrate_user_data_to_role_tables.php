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
        // Migrate admin users
        $admins = DB::table('users')->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            DB::table('admins')->insert([
                'user_id' => $admin->id,
                'phone_number' => $admin->phone_number,
                'address' => $admin->address,
                'date_of_birth' => $admin->date_of_birth,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Migrate mechanic users
        $mechanics = DB::table('users')->where('role', 'mechanic')->get();
        foreach ($mechanics as $mechanic) {
            DB::table('mechanics')->insert([
                'user_id' => $mechanic->id,
                'phone_number' => $mechanic->phone_number,
                'address' => $mechanic->address,
                'date_of_birth' => $mechanic->date_of_birth,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Migrate regular users
        $users = DB::table('users')->where('role', 'user')->get();
        foreach ($users as $user) {
            DB::table('drivers')->insert([
                'user_id' => $user->id,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'date_of_birth' => $user->date_of_birth,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear role-specific tables
        DB::table('admins')->truncate();
        DB::table('mechanics')->truncate();
        DB::table('drivers')->truncate();
    }
};

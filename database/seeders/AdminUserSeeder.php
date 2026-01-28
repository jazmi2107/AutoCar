<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AdminInformation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Create admin user
            $user = User::create([
                'name' => 'JAZMI BIN HAZIQ',
                'email' => 'admin@autocar.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]);

            // Create admin information record
            AdminInformation::create([
                'user_id' => $user->id,
            ]);

            $this->command->info('✅ Admin user created successfully!');
            $this->command->info('📧 Email: admin@autocar.com');
            $this->command->info('🔑 Password: password123');
        });
    }
}

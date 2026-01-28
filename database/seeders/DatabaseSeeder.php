<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AdminInformation;
use App\Models\Driver;
use App\Models\InsuranceCompany;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\MechanicSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@autocar.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        AdminInformation::create([
            'user_id' => $admin->id,
            'phone_number' => '0123456789',
            'address' => '123 Admin Street',
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@autocar.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        Driver::create([
            'user_id' => $user->id,
            'phone_number' => '0123456787',
            'address' => '789 User Avenue',
        ]);

        // Create Insurance Company User
        $insurance = User::create([
            'name' => 'SafeGuard Insurance',
            'email' => 'insurance@autocar.com',
            'password' => Hash::make('password'),
            'role' => 'insurance',
        ]);

        InsuranceCompany::create([
            'user_id' => $insurance->id,
            'company_name' => 'SafeGuard Insurance',
            'registration_number' => 'INS-001',
            'phone_number' => '03-12345678',
            'address' => '101 Insurance Plaza',
            'approval_status' => 'approved',
        ]);

        $this->call(MechanicSeeder::class);
    }
}

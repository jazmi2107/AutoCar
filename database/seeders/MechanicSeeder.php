<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mechanic;

class MechanicSeeder extends Seeder
{
    /**
     * Seed sample mechanics.
     */
    public function run(): void
    {
        $mechanicsData = [
            [
                'name' => 'John Mechanic',
                'email' => 'mechanic@autocar.com',
                'password' => 'password',
                'phone_number' => '0123456788',
                'address' => '456 Workshop Road, Kuala Lumpur',
                'date_of_birth' => '1990-05-15',
                'license_number' => 'MECH-001',
                'years_of_experience' => 5,
                'insurance_company_id' => 1, // SafeGuard Insurance
                'latitude' => 3.1390,
                'longitude' => 101.6869,
                'rating' => 4.5,
            ],
            [
                'name' => 'Sarah Garage',
                'email' => 'sarah.mechanic@autocar.com',
                'password' => 'password',
                'phone_number' => '0123456790',
                'address' => '789 Service Lane, Petaling Jaya',
                'date_of_birth' => '1988-09-20',
                'license_number' => 'MECH-002',
                'years_of_experience' => 8,
                'insurance_company_id' => 2, // ZURICH
                'latitude' => 3.1073,
                'longitude' => 101.6425,
                'rating' => 4.7,
            ],
            [
                'name' => 'Ahmad Workshop',
                'email' => 'ahmad.mechanic@autocar.com',
                'password' => 'password',
                'phone_number' => '0123456791',
                'address' => '12 Jalan Genting Klang, Setapak',
                'date_of_birth' => '1985-03-10',
                'license_number' => 'MECH-003',
                'years_of_experience' => 12,
                'insurance_company_id' => 3, // ALLIANCE
                'latitude' => 3.1844,
                'longitude' => 101.7224,
                'rating' => 4.8,
            ],
            [
                'name' => 'Lim Auto Service',
                'email' => 'lim.mechanic@autocar.com',
                'password' => 'password',
                'phone_number' => '0123456792',
                'address' => '88 Jalan Cheras, Cheras',
                'date_of_birth' => '1992-07-22',
                'license_number' => 'MECH-004',
                'years_of_experience' => 6,
                'insurance_company_id' => 1, // SafeGuard Insurance
                'latitude' => 3.0965,
                'longitude' => 101.7475,
                'rating' => 4.3,
            ],
            [
                'name' => 'Kumar Motor Works',
                'email' => 'kumar.mechanic@autocar.com',
                'password' => 'password',
                'phone_number' => '0123456793',
                'address' => '45 Jalan Ampang, Ampang',
                'date_of_birth' => '1987-11-05',
                'license_number' => 'MECH-005',
                'years_of_experience' => 10,
                'insurance_company_id' => 2, // ZURICH
                'latitude' => 3.1569,
                'longitude' => 101.7621,
                'rating' => 4.6,
            ],
            [
                'name' => 'Tan Car Care',
                'email' => 'tan.mechanic@autocar.com',
                'password' => 'password',
                'phone_number' => '0123456794',
                'address' => '22 Jalan Kepong, Kepong',
                'date_of_birth' => '1991-02-18',
                'license_number' => 'MECH-006',
                'years_of_experience' => 7,
                'insurance_company_id' => 3, // ALLIANCE
                'latitude' => 3.2193,
                'longitude' => 101.6382,
                'rating' => 4.4,
            ],
            [
                'name' => 'Wong Auto Repair',
                'email' => 'wong.mechanic@autocar.com',
                'password' => 'password',
                'phone_number' => '0123456795',
                'address' => '66 Jalan Sentul, Sentul',
                'date_of_birth' => '1989-08-30',
                'license_number' => 'MECH-007',
                'years_of_experience' => 9,
                'insurance_company_id' => 1, // SafeGuard Insurance
                'latitude' => 3.1849,
                'longitude' => 101.6912,
                'rating' => 4.5,
            ],
        ];

        foreach ($mechanicsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt($data['password']),
                    'role' => 'mechanic',
                ]
            );

            Mechanic::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone_number' => $data['phone_number'],
                    'address' => $data['address'],
                    'date_of_birth' => $data['date_of_birth'],
                    'license_number' => $data['license_number'],
                    'years_of_experience' => $data['years_of_experience'],
                    'insurance_company_id' => $data['insurance_company_id'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'availability_status' => 'available',
                    'rating' => $data['rating'],
                    'approval_status' => 'approved',
                ]
            );
        }
    }
}


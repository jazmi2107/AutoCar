<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Contract\Database as FirebaseDatabase;

class FirebaseSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Firebase Auth and Realtime Database with initial users';

    protected $auth;
    protected $database;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->auth = app('firebase.auth');
            $this->database = app('firebase.database');
        } catch (\Exception $e) {
            $this->error('Failed to initialize Firebase: ' . $e->getMessage());
            return 1;
        }
        $users = [
            [
                'email' => 'admin@autocar.com',
                'password' => 'password123',
                'displayName' => 'Admin User',
                'role' => 'admin',
                'profile' => [
                    'name' => 'Admin User',
                    'role' => 'admin',
                    'phone_number' => '0123456789',
                    'address' => '123 Admin Street',
                ]
            ],
            [
                'email' => 'user@autocar.com',
                'password' => 'password123',
                'displayName' => 'Regular User',
                'role' => 'user',
                'profile' => [
                    'name' => 'Regular User',
                    'role' => 'user',
                    'phone_number' => '0123456787',
                    'address' => '789 User Avenue',
                ]
            ],
            [
                'email' => 'insurance@autocar.com',
                'password' => 'password123',
                'displayName' => 'SafeGuard Insurance',
                'role' => 'insurance',
                'profile' => [
                    'name' => 'SafeGuard Insurance',
                    'role' => 'insurance',
                    'company_name' => 'SafeGuard Insurance',
                    'registration_number' => 'INS-001',
                    'phone_number' => '03-12345678',
                    'address' => '101 Insurance Plaza',
                    'approval_status' => 'approved',
                ]
            ],
            [
                'email' => 'mechanic@autocar.com',
                'password' => 'password123',
                'displayName' => 'Expert Mechanic',
                'role' => 'mechanic',
                'profile' => [
                    'name' => 'Expert Mechanic',
                    'role' => 'mechanic',
                    'phone_number' => '0123456785',
                    'address' => '456 Mechanic Road',
                    'license_number' => 'MECH-999',
                    'years_of_experience' => 10,
                    'approval_status' => 'approved',
                    'availability_status' => 'available',
                ]
            ],
        ];

        $this->info('🚀 Starting Firebase Seeding...');

        foreach ($users as $userData) {
            try {
                // 1. Create or get user in Firebase Auth
                try {
                    $userRecord = $this->auth->getUserByEmail($userData['email']);
                    $this->comment("User {$userData['email']} already exists. Updating...");
                } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                    $userRecord = $this->auth->createUser([
                        'email' => $userData['email'],
                        'password' => $userData['password'],
                        'displayName' => $userData['displayName'],
                    ]);
                    $this->info("Created Auth user: {$userData['email']}");
                }

                // 2. Set Custom Claims (Role)
                $this->auth->setCustomUserClaims($userRecord->uid, ['role' => $userData['role']]);

                // 3. Store Profile in Realtime Database
                $profileData = array_merge($userData['profile'], [
                    'uid' => $userRecord->uid,
                    'email' => $userData['email'],
                    'updated_at' => [".sv" => "timestamp"]
                ]);

                $this->database->getReference('users/' . $userRecord->uid)->update($profileData);
                
                $this->info("✅ Successfully seeded: {$userData['email']} ({$userData['role']})");

            } catch (\Exception $e) {
                $this->error("❌ Error seeding {$userData['email']}: " . $e->getMessage());
            }
        }

        $this->info('✨ Firebase Seeding Completed!');
        
        $this->table(
            ['Role', 'Email', 'Password'],
            array_map(fn($u) => [$u['role'], $u['email'], $u['password']], $users)
        );
    }
}

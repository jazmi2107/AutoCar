<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FirebaseCreateUser extends Command
{
    protected $signature = 'firebase:create-user {email} {password} {role=user}';
    protected $description = 'Create a user in Firebase Auth and RTDB';

    public function handle()
    {
        $data = [
            'name' => 'Test User',
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
            'password_confirmation' => $this->argument('password'),
            'role' => $this->argument('role'),
            'phone_number' => '1234567890',
            'address' => '123 Test St',
        ];

        // Add role specific data
        if ($data['role'] === 'user') {
            $data['date_of_birth'] = '1990-01-01';
            $data['vehicle_make'] = 'Toyota';
            $data['vehicle_model'] = 'Corolla';
            $data['plate_number'] = 'ABC-123';
        }

        $controller = new RegisterController();
        
        // We need to access the protected create method. 
        // Reflection is one way, or just instantiate the logic directly here.
        // Let's use reflection to test the actual controller logic.
        
        try {
            $reflection = new \ReflectionClass($controller);
            $createMethod = $reflection->getMethod('create');
            $createMethod->setAccessible(true);
            
            $this->info('Creating user...');
            $user = $createMethod->invoke($controller, $data);
            
            $this->info('User created successfully!');
            $this->info('UID: ' . $user->getAuthIdentifier());
            $this->info('Email: ' . $user->email);
            
            // Verify RTDB data
            $database = app('firebase.database');
            $reference = $database->getReference('users/' . $user->getAuthIdentifier());
            $snapshot = $reference->getSnapshot();
            
            if ($snapshot->exists()) {
                $this->info('User data verified in Realtime Database:');
                $this->line(json_encode($snapshot->getValue(), JSON_PRETTY_PRINT));
            } else {
                $this->error('User data NOT found in Realtime Database.');
            }

        } catch (\Exception $e) {
            $this->error('Failed to create user: ' . $e->getMessage());
        }
    }
}

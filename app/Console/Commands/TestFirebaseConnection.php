<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Laravel\Firebase\Facades\Firebase;

class TestFirebaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the connection to Firebase';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Firebase connection...');

        try {
            $auth = Firebase::auth();

            $this->info('Attempting to create a custom token to verify key format...');
            // This is a local operation that uses the private key
            $customToken = $auth->createCustomToken('test-uid');
            $this->info('Custom token created successfully (Key format is valid).');
            
            $this->info('Attempting to list users to verify API access...');
            // This requires a network call and API to be enabled
            $users = $auth->listUsers($defaultMaxResults = 1, $defaultBatchSize = 1);
            
            $count = 0;
            foreach ($users as $user) {
                $count++;
                $this->line("Found user: " . $user->uid . " (" . $user->email . ")");
            }
            
            $this->info('Successfully connected to Firebase Auth API!');
            
            if ($count === 0) {
                $this->info('No users found, but connection was successful.');
            }

        } catch (\Exception $e) {
            $this->error('Failed to connect to Firebase Auth: ' . $e->getMessage());
            if (str_contains($e->getMessage(), 'CONFIGURATION_NOT_FOUND')) {
                $this->warn('This error usually means the Identity Platform (Authentication) is not enabled in the Google Cloud Console for this project.');
            }
        }

        try {
            $this->info('Attempting to connect to Realtime Database...');
            $database = Firebase::database();
            $reference = $database->getReference('test_connection');
            $reference->set(['connected' => true, 'timestamp' => time()]);
            $this->info('Successfully wrote to Realtime Database!');
            $reference->remove(); // Clean up
        } catch (\Exception $e) {
            $this->error('Failed to connect to Realtime Database: ' . $e->getMessage());
        }

        return 0;
    }
}

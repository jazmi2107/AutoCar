<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use App\Models\FirebaseUser;
use Illuminate\Support\Facades\Http;

class FirebaseUserProvider implements UserProvider
{
    protected $auth;
    protected $model;

    public function __construct($auth, $model)
    {
        $this->auth = $auth;
        $this->model = $model;
    }

    protected function getAuth()
    {
        if ($this->auth) {
            return $this->auth;
        }

        try {
            $this->auth = app('firebase.auth');
            return $this->auth;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function retrieveById($identifier)
    {
        $auth = $this->getAuth();
        if (!$auth) return null;
        try {
            $user = $auth->getUser($identifier);
            return $this->attachUserProfile($user);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
        $auth = $this->getAuth();
        if (!$auth) {
            \Log::error('Firebase Auth not initialized in retrieveByCredentials. Check FIREBASE_CREDENTIALS.');
            return null;
        }

        if (!isset($credentials['email'])) {
            return null;
        }

        try {
            $user = $auth->getUserByEmail($credentials['email']);
            return $this->attachUserProfile($user);
        } catch (\Exception $e) {
            \Log::warning('Firebase user not found or error: ' . $e->getMessage());
            return null;
        }
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $auth = $this->getAuth();
        if (!$auth) {
            \Log::error('Firebase Auth not initialized in validateCredentials.');
            return false;
        }
        
        $email = $credentials['email'];
        $password = $credentials['password'];
        $apiKey = config('services.firebase.api_key');

        if (empty($apiKey)) {
            \Log::error('FIREBASE_API_KEY is missing in config. Cannot validate password.');
            return false;
        }

        try {
            $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$apiKey}", [
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ]);

            if (!$response->successful()) {
                \Log::warning('Firebase sign-in failed: ' . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase API request failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function attachUserProfile($authUserData)
    {
        $firebaseUser = new FirebaseUser($authUserData);
        
        try {
            // Check if firebase.database is available before resolving
            if (app()->bound('firebase.database')) {
                $database = app('firebase.database');
                $reference = $database->getReference('users/' . $authUserData->uid);
                $snapshot = $reference->getSnapshot();
                
                if ($snapshot->exists()) {
                    $profile = $snapshot->getValue();
                    foreach ($profile as $key => $value) {
                        $firebaseUser->$key = $value;
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error or ignore, return user with just Auth data
            \Log::warning('Firebase DB profile fetch failed: ' . $e->getMessage());
        }
        
        return $firebaseUser;
    }

    protected function mapUser($firebaseUser)
    {
        return new FirebaseUser($firebaseUser);
    }
}

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

    public function retrieveById($identifier)
    {
        if (!$this->auth) return null;
        try {
            $user = $this->auth->getUser($identifier);
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
        if (!$this->auth || !isset($credentials['email'])) {
            return null;
        }

        try {
            $user = $this->auth->getUserByEmail($credentials['email']);
            return $this->attachUserProfile($user);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!$this->auth) return false;
        
        $email = $credentials['email'];
        $password = $credentials['password'];
        $apiKey = env('FIREBASE_API_KEY');

        if (empty($apiKey)) {
             // Fallback to VITE key if server key missing
             $apiKey = env('VITE_FIREBASE_API_KEY');
        }

        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$apiKey}", [
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true,
        ]);

        return $response->successful();
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

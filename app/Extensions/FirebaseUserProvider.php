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

    public function __construct(FirebaseAuth $auth, $model)
    {
        $this->auth = $auth;
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        try {
            $user = $this->auth->getUser($identifier);
            return $this->attachUserProfile($user);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function retrieveByToken($identifier, $token)
    {
        // Firebase Auth doesn't use "remember me" tokens in the same way.
        // We can verify the ID token if passed.
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Not applicable for Firebase
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (!isset($credentials['email'])) {
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
            $database = app('firebase.database');
            $reference = $database->getReference('users/' . $authUserData->uid);
            $snapshot = $reference->getSnapshot();
            
            if ($snapshot->exists()) {
                $profile = $snapshot->getValue();
                foreach ($profile as $key => $value) {
                    $firebaseUser->$key = $value;
                }
            }
        } catch (\Exception $e) {
            // Ignore DB errors, return user with just Auth data
        }
        
        return $firebaseUser;
    }

    protected function mapUser($firebaseUser)
    {
        return new FirebaseUser($firebaseUser);
    }
}

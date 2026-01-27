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
            return $this->mapUser($user);
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
            return $this->mapUser($user);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $email = $credentials['email'];
        $password = $credentials['password'];
        $apiKey = env('VITE_FIREBASE_API_KEY'); // Use env variable

        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$apiKey}", [
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true,
        ]);

        return $response->successful();
    }

    protected function mapUser($firebaseUser)
    {
        return new FirebaseUser([
            'localId' => $firebaseUser->uid,
            'email' => $firebaseUser->email,
            'displayName' => $firebaseUser->displayName,
            'photoUrl' => $firebaseUser->photoUrl,
            'emailVerified' => $firebaseUser->emailVerified,
            'disabled' => $firebaseUser->disabled,
            // Add other fields as needed, potentially fetching from Firestore/RTDB here
        ]);
    }
}

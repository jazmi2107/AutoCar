<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class FirebaseUser implements Authenticatable
{
    protected $uid;
    protected $email;
    protected $displayName;
    protected $phoneNumber;
    protected $photoUrl;
    protected $customClaims;
    protected $metadata;

    // Additional fields from Firestore/RTDB
    protected $role;
    protected $attributes = [];

    public function __construct($data = [])
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
                $this->attributes[$key] = $value;
            }
        } elseif ($data instanceof \Kreait\Firebase\Auth\UserRecord) {
            $this->uid = $data->uid;
            $this->email = $data->email;
            $this->displayName = $data->displayName;
            $this->phoneNumber = $data->phoneNumber;
            $this->photoUrl = $data->photoUrl;
            $this->customClaims = $data->customClaims;
            $this->metadata = $data->metadata;
            
            // Map custom claims to attributes if needed
            if (isset($data->customClaims['role'])) {
                $this->role = $data->customClaims['role'];
            }
        }
    }

    public function getAuthIdentifierName()
    {
        return 'uid';
    }

    public function getAuthIdentifier()
    {
        return $this->uid;
    }

    public function getAuthPassword()
    {
        // Firebase handles password verification, so this is not used in the traditional sense.
        // However, Laravel might call it.
        return '';
    }

    public function getRememberToken()
    {
        return null; // Stateless or handled by session
    }

    public function setRememberToken($value)
    {
        // Not implemented
    }

    public function getRememberTokenName()
    {
        return null;
    }

    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
        return null;
    }
    
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
        }
    }

    // Helper to get role
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}

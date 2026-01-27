<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class HashUserPasswordsSeeder extends Seeder
{
    /**
     * Re-hash all user passwords to use bcrypt via the model's hashed cast.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->password = '12345678';
            $user->save();
        }
    }
}


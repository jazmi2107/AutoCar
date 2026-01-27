<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\InsuranceCompany;

class EnsureInsuranceCompaniesSeeder extends Seeder
{
    /**
     * Ensure each insurance-role user has an associated insurance company record.
     */
    public function run(): void
    {
        $users = User::where('role', 'insurance')->get();

        foreach ($users as $user) {
            if ($user->insuranceCompany) {
                continue;
            }

            InsuranceCompany::create([
                'user_id' => $user->id,
                'company_name' => $user->name,
                'registration_number' => 'AUTO-' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'website' => null,
                'approval_status' => 'approved',
            ]);
        }
    }
}


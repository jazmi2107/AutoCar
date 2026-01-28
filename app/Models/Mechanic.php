<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'address',
        'date_of_birth',
        'license_number',
        'years_of_experience',
        'insurance_company_id',
        'insurance_name',
        'availability_status',
        'approval_status',
        'rating',
        'latitude',
        'longitude',
        'profile_picture',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }
}

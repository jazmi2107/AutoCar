<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistanceRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'insurance_company_id',
        'mechanic_id',
        'insurance_name',
        'breakdown_type',
        'name',
        'phone_number',
        'plate_number',
        'vehicle_make',
        'vehicle_model',
        'location_address',
        'latitude',
        'longitude',
        'status',
        'notes',
        'estimated_cost',
        'final_cost',
        'payment_status',
        'distance_fee',
        'night_surcharge',
        'total_cost',
        'accepted_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'mechanic_rating',
        'mechanic_review',
        'rated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rated_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
    ];

    /**
     * Get the user that owns the assistance request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the mechanic assigned to the assistance request.
     */
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    /**
     * Get the insurance company associated with the assistance request.
     */
    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    /**
     * Get the service icon based on breakdown type.
     */
    public function getServiceIconAttribute()
    {
        $icons = [
            'Engine Problem' => 'screwdriver-wrench',
            'Battery & Electrical' => 'battery-full',
            'Flat Tire' => 'life-ring',
            'Lock Out' => 'unlock',
            'Accident' => 'car-crash',
            'Transmission Problem' => 'gears',
            'Towing' => 'truck-pickup',
        ];

        return $icons[$this->breakdown_type] ?? 'wrench';
    }
}

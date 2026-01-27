<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'base_price',
        'price_per_km',
        'midnight_surcharge_rate',
        'fuel_adjustment_factor',
        'last_fuel_price',
        'last_fuel_check',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'price_per_km' => 'decimal:2',
        'midnight_surcharge_rate' => 'decimal:2',
        'fuel_adjustment_factor' => 'decimal:2',
        'last_fuel_price' => 'decimal:2',
        'last_fuel_check' => 'datetime',
    ];
}

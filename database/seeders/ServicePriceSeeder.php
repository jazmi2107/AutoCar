<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServicePrice;

class ServicePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_type' => 'Engine Problem',
                'base_price' => 180.00,
                'price_per_km' => 3.00,
                'midnight_surcharge_rate' => 30.00,
                'fuel_adjustment_factor' => 0.00,
                'last_fuel_price' => 2.05, // Current RON95 baseline
            ],
            [
                'service_type' => 'Battery & Electrical',
                'base_price' => 120.00,
                'price_per_km' => 3.00,
                'midnight_surcharge_rate' => 30.00,
                'fuel_adjustment_factor' => 0.00,
                'last_fuel_price' => 2.05,
            ],
            [
                'service_type' => 'Flat Tire',
                'base_price' => 80.00,
                'price_per_km' => 3.00,
                'midnight_surcharge_rate' => 30.00,
                'fuel_adjustment_factor' => 0.00,
                'last_fuel_price' => 2.05,
            ],
            [
                'service_type' => 'Lock Out',
                'base_price' => 100.00,
                'price_per_km' => 3.00,
                'midnight_surcharge_rate' => 30.00,
                'fuel_adjustment_factor' => 0.00,
                'last_fuel_price' => 2.05,
            ],
            [
                'service_type' => 'Accident',
                'base_price' => 250.00,
                'price_per_km' => 4.00, // Higher per km for towing
                'midnight_surcharge_rate' => 30.00,
                'fuel_adjustment_factor' => 0.00,
                'last_fuel_price' => 2.05,
            ],
            [
                'service_type' => 'Transmission Problem',
                'base_price' => 200.00,
                'price_per_km' => 3.00,
                'midnight_surcharge_rate' => 30.00,
                'fuel_adjustment_factor' => 0.00,
                'last_fuel_price' => 2.05,
            ],
        ];

        foreach ($services as $service) {
            ServicePrice::updateOrCreate(
                ['service_type' => $service['service_type']],
                $service
            );
        }
    }
}

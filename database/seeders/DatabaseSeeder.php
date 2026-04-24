<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Order matters — locations and accounts must exist
        // before vehicles and bookings reference them
        $this->call([
            LocationSeeder::class,
            VehicleSeeder::class,
            AccountSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
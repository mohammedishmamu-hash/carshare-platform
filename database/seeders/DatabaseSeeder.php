<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch vehicle data once and pass to both seeders
        // This avoids duplicate API calls and ensures both seeders
        // work with the same data
        try {
            $response = Http::timeout(config('services.vehicles_api.timeout'))
                ->get(config('services.vehicles_api.url'));

            if (!$response->successful()) {
                $this->command->error('Vehicles API returned an error: ' . $response->status());
                return;
            }

            $vehiclesData = $response->json('data');

        } catch (\Exception $e) {
            $this->command->error('Could not reach the vehicles API: ' . $e->getMessage());
            return;
        }

        if (empty($vehiclesData)) {
            $this->command->warn('No vehicles returned from API. Aborting seed.');
            return;
        }

        // Pass the same API response to both seeders
        $this->call(LocationSeeder::class, false, ['vehiclesData' => $vehiclesData]);
        $this->call(VehicleSeeder::class, false, ['vehiclesData' => $vehiclesData]);
        $this->call(AccountSeeder::class);
        $this->call(BookingSeeder::class);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch vehicles from the external API
        try {
            $response = Http::timeout(config('services.vehicles_api.timeout'))
                ->get(config('services.vehicles_api.url'));

            if (!$response->successful()) {
                $this->command->error('Vehicles API returned an error: ' . $response->status());
                return;
            }

            $vehicles = $response->json('data');

        } catch (\Exception $e) {
            $this->command->error('Could not reach the vehicles API: ' . $e->getMessage());
            return;
        }

        if (empty($vehicles)) {
            $this->command->warn('No vehicles returned from API. Skipping location seeding.');
            return;
        }

        // Extract unique locations from the vehicle data
        $seededCount = 0;

        foreach ($vehicles as $vehicle) {
            $locationId = $vehicle['location_id'] ?? null;

            // Skip if no location_id present
            if (!$locationId) {
                continue;
            }

            // Use updateOrCreate so re-running the seeder is safe
            Location::updateOrCreate(
                ['id' => $locationId],
                ['description' => $vehicle['location_description'] ?? null]
            );

            $seededCount++;
        }

        $this->command->info("Seeded {$seededCount} locations.");
    }
}
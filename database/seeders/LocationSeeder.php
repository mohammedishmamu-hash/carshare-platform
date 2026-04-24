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

            // Check if this location_id already exists with a different description
            $existing = Location::find($locationId);

            if ($existing) {
                // Log a warning if source data has conflicting descriptions
                // for the same location_id — keep the first one we saw
                if ($existing->description !== $vehicle['location_description']) {
                    Log::warning('Conflicting location description for location_id ' . $locationId);
                    $this->command->warn(
                        'Conflicting location data: location_id ' . $locationId .
                        ' has multiple descriptions. Keeping "' . $existing->description . '"'
                    );
                }
                continue;
            }

            Location::create([
                'id'          => $locationId,
                'description' => $vehicle['location_description'] ?? null,
            ]);

            $seededCount++;
        }

        $this->command->info("Seeded {$seededCount} locations.");
    }
}
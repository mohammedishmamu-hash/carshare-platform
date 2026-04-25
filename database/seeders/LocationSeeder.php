<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationSeeder extends Seeder
{
    public function run(array $vehiclesData = []): void
    {
        if (empty($vehiclesData)) {
            $this->command->warn('No vehicle data provided. Skipping location seeding.');
            return;
        }

        $seededCount = 0;

        foreach ($vehiclesData as $vehicle) {
            $locationId = $vehicle['location_id'] ?? null;

            if (!$locationId) {
                continue;
            }

            $existing = Location::find($locationId);

            if ($existing) {
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
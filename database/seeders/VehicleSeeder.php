<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
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
            $this->command->warn('No vehicles returned from API. Skipping vehicle seeding.');
            return;
        }

        $seededCount = 0;
        $skippedCount = 0;

        foreach ($vehicles as $vehicle) {
            // Validate required fields before inserting
            if (!$this->isValidVehicle($vehicle)) {
                $skippedCount++;
                Log::warning('Skipping invalid vehicle record', ['data' => $vehicle]);
                continue;
            }

            // updateOrCreate makes the seeder safe to re-run
            Vehicle::updateOrCreate(
                ['id' => $vehicle['id']],
                [
                    'make'        => trim($vehicle['make']),
                    'model'       => trim($vehicle['model']),
                    'year'        => (int) $vehicle['year'],
                    'colour'      => trim($vehicle['colour']),
                    'location_id' => $vehicle['location_id'] ?? null,
                ]
            );

            $seededCount++;
        }

        $this->command->info("Seeded {$seededCount} vehicles. Skipped {$skippedCount} invalid records.");
    }

    // Check that a vehicle record has all the fields we need
    private function isValidVehicle(array $vehicle): bool
    {
        $requiredFields = ['id', 'make', 'model', 'year', 'colour'];

        foreach ($requiredFields as $field) {
            if (empty($vehicle[$field])) {
                return false;
            }
        }

        // Year should be a realistic number
        $year = (int) $vehicle['year'];
        if ($year < 1990 || $year > (int) date('Y') + 1) {
            return false;
        }

        return true;
    }
}
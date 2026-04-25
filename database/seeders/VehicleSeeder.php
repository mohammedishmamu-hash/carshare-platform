<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VehicleSeeder extends Seeder
{
    public function run(array $vehiclesData = []): void
    {
        if (empty($vehiclesData)) {
            $this->command->warn('No vehicle data provided. Skipping vehicle seeding.');
            return;
        }

        $seededCount = 0;
        $skippedCount = 0;

        foreach ($vehiclesData as $vehicle) {
            if (!$this->isValidVehicle($vehicle)) {
                $skippedCount++;
                Log::warning('Skipping invalid vehicle record', ['data' => $vehicle]);
                continue;
            }

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
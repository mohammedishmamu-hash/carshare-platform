<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('bookings.json');

        if (!file_exists($path)) {
            $this->command->error('bookings.json not found at project root.');
            return;
        }

        $contents = file_get_contents($path);
        $bookings = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('bookings.json is not valid JSON.');
            return;
        }

        // Get all valid vehicle IDs so we can skip orphaned bookings
        $validVehicleIds = Vehicle::pluck('id')->toArray();

        $seededCount = 0;
        $skippedCount = 0;

        foreach ($bookings as $booking) {
            // Validate the booking before inserting
            if (!$this->isValidBooking($booking, $validVehicleIds)) {
                $skippedCount++;
                Log::warning('Skipping invalid booking record', ['data' => $booking]);
                continue;
            }

            Booking::updateOrCreate(
                ['id' => $booking['id']],
                [
                    'vehicle_id'  => $booking['car_id'],
                    'account_id'  => $booking['account_id'],
                    'started_at'  => $booking['started_at'],
                    'ended_at'    => $booking['ended_at'],
                ]
            );

            $seededCount++;
        }

        $this->command->info("Seeded {$seededCount} bookings. Skipped {$skippedCount} invalid records.");
    }

    private function isValidBooking(array $booking, array $validVehicleIds): bool
    {
        // Check required fields exist
        $requiredFields = ['id', 'car_id', 'account_id', 'started_at', 'ended_at'];

        foreach ($requiredFields as $field) {
            if (empty($booking[$field])) {
                return false;
            }
        }

        // Skip bookings that reference a vehicle not in our database
        if (!in_array($booking['car_id'], $validVehicleIds)) {
            return false;
        }

        // Validate that the dates are actually valid datetime strings
        try {
            $start = new \DateTime($booking['started_at']);
            $end   = new \DateTime($booking['ended_at']);

            // ended_at should be after started_at
            if ($end <= $start) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
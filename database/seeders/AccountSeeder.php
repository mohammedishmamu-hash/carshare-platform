<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        // Extract unique account IDs from bookings.json
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

        // Get unique account IDs from the bookings
        $accountIds = collect($bookings)
            ->pluck('account_id')
            ->filter()
            ->unique()
            ->values();

        foreach ($accountIds as $accountId) {
            Account::updateOrCreate(['id' => $accountId]);
        }

        $this->command->info("Seeded {$accountIds->count()} accounts.");
    }
}
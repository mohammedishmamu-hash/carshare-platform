<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = ['id', 'make', 'model', 'year', 'colour', 'location_id'];

    // Cast year to integer so it's always returned as a number
    protected $casts = [
        'year' => 'integer',
    ];

    // Each vehicle belongs to one location
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    // A vehicle can have many bookings over time
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Get all vehicles enriched with location and booking data
    public static function getEnrichedFleet(): array
    {
        $vehicles = self::with('location')->withCount('bookings')->get();
        $now = now();

        return $vehicles->map(function ($vehicle) use ($now) {
            // Using Query Builder for availability — explicit date range check
            $isAvailable = DB::table('bookings')
                ->where('vehicle_id', $vehicle->id)
                ->where('started_at', '<=', $now)
                ->where('ended_at', '>=', $now)
                ->doesntExist();

            return [
                'id'             => $vehicle->id,
                'make'           => $vehicle->make,
                'model'          => $vehicle->model,
                'year'           => $vehicle->year,
                'colour'         => $vehicle->colour,
                'location'       => $vehicle->location->description ?? 'Location unavailable',
                'total_bookings' => $vehicle->bookings_count,
                'is_available'   => $isAvailable,
            ];
        })->toArray();
    }
}
<?php

namespace App\Models;

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

    // Check if vehicle is currently booked right now
    public function isAvailable(): bool
    {
        $now = now();

        return !$this->bookings()
            ->where('started_at', '<=', $now)
            ->where('ended_at', '>=', $now)
            ->exists();
    }
}
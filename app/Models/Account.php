<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = ['id'];

    // An account can have many bookings
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['description'];

    // A location can have many vehicles parked at it
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = ['id', 'vehicle_id', 'account_id', 'started_at', 'ended_at'];

    // Cast dates so they're always Carbon instances
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
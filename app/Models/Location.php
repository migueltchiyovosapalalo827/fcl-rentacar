<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'active',
    ];

    public function pickups()
    {
        return $this->hasMany(Reservation::class, 'pickup_location_id');
    }

    public function dropoffs()
    {
        return $this->hasMany(Reservation::class, 'dropoff_location_id');
    }
}

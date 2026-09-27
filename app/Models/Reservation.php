<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'car_id',
        'driver_id',
        'pickup_location_id',
        'dropoff_location_id',
        'start_date',
        'end_date',
        'purpose',
        'with_driver',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'with_driver' => 'boolean',
        'total_amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function dropoffLocation()
    {
        return $this->belongsTo(Location::class, 'dropoff_location_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function review()
    {
        return $this->hasOne(ReservationReview::class);
    }
}

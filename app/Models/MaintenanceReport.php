<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'technician_id',
        'reservation_id',
        'description',
        'status',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

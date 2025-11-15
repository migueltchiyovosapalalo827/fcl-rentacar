<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'plate_number',
        'price_per_day',
        'status',
        'year',
        'km',
        'image',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function maintenanceReports()
    {
        return $this->hasMany(MaintenanceReport::class);
    }
}

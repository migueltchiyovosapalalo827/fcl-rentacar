<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'amount',
        'refunded',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded' => 'boolean',
        'refunded_at' => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

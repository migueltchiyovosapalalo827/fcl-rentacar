<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_review_id',
        'type',
        'description',
        'value',
        'used',
        'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->belongsTo(ReservationReview::class, 'reservation_review_id');
    }
}

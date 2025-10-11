<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'mekanik_id',
        'rating',
        'comment',
    ];

    /**
     * Relasi dengan booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relasi dengan user (yang memberikan review).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan mekanik.
     */
    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'mechanic_id', 'motor_id', 'time_slot_id', 'status','booking_id']; // Tambahkan time_slot_id

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function motor()
    {
        return $this->belongsTo(KepemilikanMotor::class, 'motor_id');
    }

    public function services()
    {
        return $this->hasMany(BookingService::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}

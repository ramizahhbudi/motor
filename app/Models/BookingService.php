<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'service_specification_id', 'note'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function serviceSpecification()
    {
        return $this->belongsTo(ServiceSpecification::class);
    }
}

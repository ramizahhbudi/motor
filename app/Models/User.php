<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pin',
        'role',
        'phone',
    ];

    protected $hidden = [
        // 'password',
        'remember_token',
        'pin',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email' => 'encrypted', 
        'phone' => 'encrypted',
        'pin'   => 'hashed',
    ];
    /**
     * Relasi untuk bookings yang dilakukan oleh user.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi untuk jenis motor yang dimiliki oleh user.
     */
    public function kepemilikanMotors()
    {
        return $this->hasMany(KepemilikanMotor::class);
    }

    /**
     * Relasi untuk reviews yang diberikan oleh user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope untuk role tertentu.
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah mekanik.
     */
    public function isMekanik()
    {
        return $this->role === 'mekanik';
    }

    /**
     * Relasi untuk booking sebagai mekanik.
     */
    public function assignedBookings()
    {
        return $this->hasMany(Booking::class, 'mekanik_id');
    }
}

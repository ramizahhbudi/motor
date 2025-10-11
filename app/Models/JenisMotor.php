<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMotor extends Model
{
    use HasFactory;

    protected $fillable = [
        'merek',
        'tipe',
        'tahun',
        'gambar',
    ];

    /**
     * Relasi ke kepemilikan motor.
     * Satu jenis motor bisa dimiliki oleh banyak pengguna.
     */
    public function kepemilikanMotors()
    {
        return $this->hasMany(KepemilikanMotor::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KepemilikanMotor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'jenis_motor_id', 'nomor_rangka', 'nomor_mesin', 'plat'];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

// KepemilikanMotor.php
public function jenisMotor()
{
    return $this->belongsTo(JenisMotor::class, 'jenis_motor_id');
}

}

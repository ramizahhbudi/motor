<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisMotor;

class JenisMotorSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk jenis motor.
     *
     * @return void
     */
    public function run()
    {
        $jenisMotors = [
            [
                'merek' => 'Honda',
                'tipe' => 'CBR 150R',
                'tahun' => 2023,
                'gambar' => 'img/cbr150r.jpg', // Path gambar
            ],
            [
                'merek' => 'Honda',
                'tipe' => 'Vario 160',
                'tahun' => 2022,
                'gambar' => 'img/vario160.jpg',
            ],
            [
                'merek' => 'Yamaha',
                'tipe' => 'NMax 155',
                'tahun' => 2023,
                'gambar' => 'img/nmax155.jpg',
            ],
            [
                'merek' => 'Yamaha',
                'tipe' => 'Aerox 155',
                'tahun' => 2022,
                'gambar' => 'img/aerox155.jpeg',
            ],
        ];

        foreach ($jenisMotors as $motor) {
            JenisMotor::create($motor);
        }
    }
}

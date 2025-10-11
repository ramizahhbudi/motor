<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = ['Servis Mesin', 'Ganti Sparepart', 'Penggantian Ban', 'Ganti Oli'];

        foreach ($services as $service) {
            Service::create(['name' => $service]);
        }
    }
}

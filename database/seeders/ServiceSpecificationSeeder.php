<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceSpecification;

class ServiceSpecificationSeeder extends Seeder
{
    public function run()
    {
        $specifications = [
            'Servis Mesin' => [
                ['name' => 'Tune-Up Mesin', 'price' => 100000, 'stock' => 8888], // Unlimited stock
                ['name' => 'Overhaul Mesin', 'price' => 300000, 'stock' => 8888], // Unlimited stock
            ],
            'Ganti Sparepart' => [
                ['name' => 'Lampu', 'price' => 50000, 'stock' => 200],
                ['name' => 'Knalpot', 'price' => 150000, 'stock' => 150],
            ],
            'Penggantian Ban' => [
                ['name' => 'Ban Depan', 'price' => 200000, 'stock' => 800],
                ['name' => 'Ban Belakang', 'price' => 250000, 'stock' => 1000],
            ],
            'Ganti Oli' => [
                ['name' => 'Oli Mesin', 'price' => 75000, 'stock' => 500], // Unlimited stock
                ['name' => 'Oli Gardan', 'price' => 50000, 'stock' => 500],
            ],
        ];

        foreach ($specifications as $serviceName => $specs) {
            $service = Service::where('name', $serviceName)->first();
            foreach ($specs as $spec) {
                ServiceSpecification::create([
                    'service_id' => $service->id,
                    'name' => $spec['name'],
                    'price' => $spec['price'],
                    'stock' => $spec['stock'],
                ]);
            }
        }
    }
}

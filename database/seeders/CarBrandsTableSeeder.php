<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarBrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('car_brands')->truncate();
        $brands = [
            ['name' => 'Hyundai', 'slug' => 'hyundai', 'logo_path' => '/storage/car_brands/hyundai.png'],
            ['name' => 'Honda', 'slug' => 'honda', 'logo_path' => '/storage/car_brands/honda.png'],
            ['name' => 'Maruti', 'slug' => 'maruti', 'logo_path' => '/storage/car_brands/maruti.png'],
            ['name' => 'Toyota', 'slug' => 'toyota', 'logo_path' => '/storage/car_brands/toyota.png'],
            ['name' => 'Nissan', 'slug' => 'nissan', 'logo_path' => '/storage/car_brands/nissan.png'],
            ['name' => 'Volkswagen', 'slug' => 'volkswagen', 'logo_path' => '/storage/car_brands/volkswagen.png'],
            ['name' => 'Ford', 'slug' => 'ford', 'logo_path' => '/storage/car_brands/ford.png'],
            ['name' => 'Skoda', 'slug' => 'skoda', 'logo_path' => '/storage/car_brands/skoda.png'],
            ['name' => 'Chevrolet', 'slug' => 'chevrolet', 'logo_path' => '/storage/car_brands/chevrolet.png'],
            ['name' => 'Renault', 'slug' => 'renault', 'logo_path' => '/storage/car_brands/renault.png'],
            ['name' => 'Mahindra', 'slug' => 'mahindra', 'logo_path' => '/storage/car_brands/mahindra.png'],
            ['name' => 'TATA', 'slug' => 'tata', 'logo_path' => '/storage/car_brands/tata.png'],
            ['name' => 'Mercedes', 'slug' => 'mercedes', 'logo_path' => '/storage/car_brands/mercedes.png'],
            ['name' => 'Fiat', 'slug' => 'fiat', 'logo_path' => '/storage/car_brands/fiat.png'],
            ['name' => 'Porsche', 'slug' => 'porsche', 'logo_path' => '/storage/car_brands/porsche.png'],
            ['name' => 'Audi', 'slug' => 'audi', 'logo_path' => '/storage/car_brands/audi.png'],
            ['name' => 'BMW', 'slug' => 'bmw', 'logo_path' => '/storage/car_brands/bmw.png'],
            ['name' => 'Mitsubishi', 'slug' => 'mitsubishi', 'logo_path' => '/storage/car_brands/mitsubishi.png'],
            ['name' => 'Volvo', 'slug' => 'volvo', 'logo_path' => '/storage/car_brands/volvo.png'],
            ['name' => 'Land Rover', 'slug' => 'land-rover', 'logo_path' => '/storage/car_brands/land-rover.png'],
            ['name' => 'Test', 'slug' => 'test', 'logo_path' => '/storage/car_brands/maruti.png'],
            ['name' => 'Datsun', 'slug' => 'datsun', 'logo_path' => '/storage/car_brands/datsun.png'],
            ['name' => 'Hindustan Motors', 'slug' => 'hindustan-motors', 'logo_path' => '/storage/car_brands/hindustan-motors.png'],
            ['name' => 'JAGUAR', 'slug' => 'jaguar', 'logo_path' => '/storage/car_brands/jaguar.png'],
            ['name' => 'ISUZU', 'slug' => 'isuzu', 'logo_path' => '/storage/car_brands/isuzu.png'],
            ['name' => 'LEXUS', 'slug' => 'lexus', 'logo_path' => '/storage/car_brands/lexus.png'],
            ['name' => 'KIA', 'slug' => 'kia', 'logo_path' => '/storage/car_brands/kia.png'],
            ['name' => 'MG', 'slug' => 'mg', 'logo_path' => '/storage/car_brands/mg.png'],
        ];
        foreach ($brands as $brand) {
            DB::table('car_brands')->insert([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'logo_path' => $brand['logo_path'],
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

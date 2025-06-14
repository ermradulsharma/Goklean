<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('car_types')->truncate();
        $types = [
            ['name' => 'SUV', 'slug' => 'suv', 'image_path' => '/storage/car_types/suv.png'],
            ['name' => 'SEDAN', 'slug' => 'sedan', 'image_path' => '/storage/car_types/sedan.png'],
            ['name' => 'HATCHBACK', 'slug' => 'hatchback', 'image_path' => '/storage/car_types/hatchback.png'],
            ['name' => 'CROSSOVER', 'slug' => 'crossover', 'image_path' => '/storage/car_types/crossover.png'],
        ];
        foreach ($types as $type) {
            DB::table('car_types')->insert([
                'name' => $type['name'],
                'slug' => $type['slug'],
                'image_path' => $type['image_path'],
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

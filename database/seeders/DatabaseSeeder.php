<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            CarBrandsTableSeeder::class,
            // CarModelsTableSeeder::class,
            CarColorsTableSeeder::class,
            // CarYearsTableSeeder::class,
            CarTypesTableSeeder::class,
            // CarFuelTypesTableSeeder::class,
            // CarGearTypesTableSeeder::class,
            // CarTransmissionTypesTableSeeder::class,
            // CarBodyTypesTableSeeder::class,
            // CarDoorsTableSeeder::class,
            // CarSeatsTableSeeder::class,
            // CarAirbagsTableSeeder::class,
            // CarAirConditioningTableSeeder::class,
            // CarPowerWindowsTableSeeder::class,
            // CarPowerSteeringTableSeeder::class,
            // CarPowerMirrorsTableSeeder::class,
            // CarPowerLocksTableSeeder::class,
        ]);
    }
}

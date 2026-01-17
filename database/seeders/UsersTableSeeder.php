<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = City::create([
            'name' => 'Default City',
            'slug' => 'default-city',
            'code' => 'DCIT',
            'is_active' => true,
        ]);

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role' => 'admin',
            'email' => 'admin@goklean.com',
            'email_verified_at' => now(),
            'mobile' => '1234567890',
            'mobile_verified_at' => now(),
            'city_id' => $city->id,
            'password' => Hash::make('admin123'),
            'is_active' => true,
        ]);
    }
}

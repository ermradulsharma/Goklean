<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('car_colors')->truncate();
        $colors = [
            ['name' => 'ASH', 'slug' => 'ash', 'color_code' => '#B2BEB5'],
            ['name' => 'BEIGE', 'slug' => 'beige', 'color_code' => '#F5F5DC'],
            ['name' => 'BLACK', 'slug' => 'black', 'color_code' => '#000000'],
            ['name' => 'BLUE', 'slug' => 'blue', 'color_code' => '#0000FF'],
            ['name' => 'BROWN', 'slug' => 'brown', 'color_code' => '#A52A2A'],
            ['name' => 'BROWN', 'slug' => 'brown', 'color_code' => '#A52A2A'],
            ['name' => 'BROWN METALIC', 'slug' => 'brown-metalic', 'color_code' => '#8B4513'],
            ['name' => 'CHAMPAGNE', 'slug' => 'champagne', 'color_code' => '#F7E7CE'],
            ['name' => 'GOLDEN', 'slug' => 'golden', 'color_code' => '#FFD700'],
            ['name' => 'GOLDEN DUST', 'slug' => 'golden-dust', 'color_code' => '#E1C16E'],
            ['name' => 'GOLDEN PLATINUM', 'slug' => 'golden-platinum', 'color_code' => '#D4AF37'],
            ['name' => 'GREEN', 'slug' => 'green', 'color_code' => '#008000'],
            ['name' => 'GREEN LIME', 'slug' => 'green-lime', 'color_code' => '#32CD32'],
            ['name' => 'GREY', 'slug' => 'grey', 'color_code' => '#808080'],
            ['name' => 'MAROON', 'slug' => 'maroon', 'color_code' => '#800000'],
            ['name' => 'ORANGE', 'slug' => 'orange', 'color_code' => '#FFA500'],
            ['name' => 'PINK', 'slug' => 'pink', 'color_code' => '#FFC0CB'],
            ['name' => 'PURPLE', 'slug' => 'purple', 'color_code' => '#800080'],
            ['name' => 'RED', 'slug' => 'red', 'color_code' => '#FF0000'],
            ['name' => 'RED PEARL', 'slug' => 'red-pearl', 'color_code' => '#990000'],
            ['name' => 'SILVER', 'slug' => 'silver', 'color_code' => '#C0C0C0'],
            ['name' => 'SILVER METALIC', 'slug' => 'silver-metalic', 'color_code' => '#AFAFAF'],
            ['name' => 'STAR DUST', 'slug' => 'star-dust', 'color_code' => '#E5E4E2'],
            ['name' => 'TITANIUM', 'slug' => 'titanium', 'color_code' => '#878681'],
            ['name' => 'WHITE', 'slug' => 'white', 'color_code' => '#FFFFFF'],
            ['name' => 'WHITE PEARL', 'slug' => 'white-pearl', 'color_code' => '#F8F8FF'],
            ['name' => 'YELLOW', 'slug' => 'yellow', 'color_code' => '#FFFF00'],
            ['name' => 'Aquamarine', 'slug' => 'aquamarine', 'color_code' => '#7FFFD4'],
            ['name' => 'STEEL', 'slug' => 'steel', 'color_code' => '#4682B4'],
        ];

        foreach ($colors as $color) {
            DB::table('car_colors')->insert([
                'name' => $color['name'],
                'slug' => $color['slug'],
                'color_code' => $color['color_code'],
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

<?php
namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Models\City;

class HomeScreenController
{
    public function getBanners()
    {
        $banners = Banner::active()->orderBy('sort_order')->get(['id', 'title', 'image_path']);
        return response()->json($banners, 200);
    }

    public function getCities()
    {
        $cities = City::active()->get(['id', 'name']);
        return response()->json($cities, 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Models\City;
use Symfony\Component\HttpFoundation\Response;

class HomeScreenController
{
    public function getBanners()
    {
        $banners = Banner::active()->orderBy('sort_order')->get(['id', 'title', 'image_path']);
        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'message' => 'Banners fetched successfully.',
            'data' => $banners
        ], Response::HTTP_OK);
    }

    public function getCities()
    {
        $cities = City::active()->get(['id', 'name']);
        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'message' => 'Cities fetched successfully.',
            'data' => $cities
        ], Response::HTTP_OK);
    }
}

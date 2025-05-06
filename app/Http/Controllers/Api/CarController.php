<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Http\Controllers\Controller;
use App\Http\Transformers\SearchCarTransformer;
use App\Models\CarColor;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function searchCar(Request $request)
    {
        $results = [];
        $searchStrings = explode(" ", $request->q);
        foreach ($searchStrings as $searchString) {
            $cars = Car::select('id', 'name', 'car_brand_id', 'car_type_id', 'image_path')->active()->where('name', 'like', '%' . $searchString . '%')->with('brand:id,name')->limit(25)->get();
            foreach ($cars as $car) {
                array_push($results, $car);
            }
            $brandCars = Car::select('id', 'name', 'car_brand_id', 'car_type_id', 'image_path')->active()->with('brand:id,name')->whereHas('brand', function ($q) use ($searchString) {
                $q->where('name', 'like', '%' . $searchString . '%');
            })->limit(25)->get();
            foreach ($brandCars as $brandCar) {
                array_push($results, $brandCar);
            }
        }
        $carResults = fractal($results, new SearchCarTransformer())->toArray();
        return response()->json($carResults["data"], 200);
    }

    public function getColorVariants()
    {
        return CarColor::active()->get(['id', 'name']);
    }
}

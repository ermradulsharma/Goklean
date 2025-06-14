<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasePrice;
use App\Models\CarType;
use App\Models\Wash;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BasePriceCrudController extends Controller
{
    /**
     * Base Prices Page
     *
     * @return \Inertia\Response
     */
    /* public function index()
    {
        $car_types = CarType::with('prices')->orderBy('id', 'asc')->get();
        $washes = Wash::get();

        $carTypes = $car_types->map(function ($item) {
            return [
                'id' => $item->id,
                'key' => $item->slug,
                'name' => $item->name,
                'prices'  => $item->prices->mapWithKeys(function ($price) {
                    return [$price['pivot']['wash_qty'] => $price['pivot']['price']];
                })->all(),
            ];
        });

        return Inertia::render('Admin/BasePrices', [
            'washes' => $washes,
            'carTypes' => $carTypes->all()
        ]);
    } */

    public function index()
    {
        $carTypes = CarType::with('prices')->orderBy('id', 'asc')->get()->map(function ($carType) {
            return [
                'id' => $carType->id,
                'key' => $carType->slug,
                'name' => $carType->name,
                'prices' => $carType->prices->mapWithKeys(function ($price) {
                    $washQty = $price->pivot->wash_qty ?? null;
                    $priceValue = $price->pivot->price ?? null;
                    return $washQty !== null ? [$washQty => $priceValue] : [];
                }),
            ];
        });

        $washes = Wash::all();

        return Inertia::render('Admin/BasePrices', [
            'washes' => $washes,
            'carTypes' => $carTypes,
        ]);
    }

    /**
     * Update Base Prices
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $prices = [];

        foreach ($request->prices as $price) {
            $items = [];
            foreach ($price['prices'] as $key => $value) {
                $items[$key] = ['price' => $value];
            }
            $prices[$price['key']] = $items;
        }

        $carTypes = CarType::get();

        foreach ($carTypes as $carType) {
            $carType->prices()->sync($prices[$carType->slug]);
        }

        return redirect()->back()->with('successMessage', 'Prices update successfully');
    }
}

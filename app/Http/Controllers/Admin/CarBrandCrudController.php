<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CarBrandFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarBrandRequest;
use App\Http\Requests\Admin\UpdateCarBrandRequest;
use App\Models\CarBrand;
use App\Transformers\Admin\CarBrandTransformer;
use Inertia\Inertia;

class CarBrandCrudController extends Controller
{
    /**
     * List all Car Brands
     *
     * @param CarBrandFilters $filters
     * @return \Inertia\Response
     */
    public function index(CarBrandFilters $filters)
    {
        return Inertia::render('Admin/CarBrands', [
            'carBrands' => function () use($filters) {
                return fractal(CarBrand::filter($filters)
                    ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new CarBrandTransformer())->toArray();
            },
        ]);
    }

    /**
     * Store a CarBrand
     *
     * @param StoreCarBrandRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCarBrandRequest $request)
    {
        CarBrand::create($request->validated());
        return redirect()->back()->with('successMessage', 'Car Brand was successfully added!');
    }

    /**
     * Show a CarBrand
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(CarBrand::find($id), new CarBrandTransformer())->toArray();
    }

    /**
     * Edit a CarBrand
     *
     * @param $id
     * @return CarBrand|CarBrand[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return CarBrand::find($id);
    }

    /**
     * Update a CarBrand
     *
     * @param UpdateCarBrandRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCarBrandRequest $request, $id)
    {
        $carBrand = CarBrand::find($id);
        $carBrand->update($request->validated());
        return redirect()->back()->with('successMessage', 'Car Brand was successfully updated!');
    }

    /**
     * Delete a CarBrand
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $carBrand = CarBrand::find($id);

            if(!$carBrand->canSecureDelete('cars')) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Car Brand! Remove from all associated cars and try again!');
            }

            $carBrand->secureDelete('cars');
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('errorMessage', 'Unable to Delete Car Brand . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Car Brand was successfully deleted!');
    }
}

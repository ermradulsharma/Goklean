<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CarFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarRequest;
use App\Http\Requests\Admin\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Transformers\Admin\CarTransformer;
use Inertia\Inertia;

class CarCrudController extends Controller
{
    /**
     * List all Cars
     *
     * @param CarFilters $filters
     * @return \Inertia\Response
     */
    public function index(CarFilters $filters)
    {
        return Inertia::render('Admin/Cars', [
            'cars' => function () use($filters) {
                return fractal(Car::with(['type:id,name','brand:id,name'])->filter($filters)
                    ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new CarTransformer())->toArray();
            },
            'brands' => CarBrand::select('id', 'name')->get(),
            'types' => CarType::select('id', 'name')->get()
        ]);
    }

    /**
     * Store a Car
     *
     * @param StoreCarRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCarRequest $request)
    {
        Car::create($request->validated());
        return redirect()->back()->with('successMessage', 'Car was successfully added!');
    }

    /**
     * Show a Car
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(Car::find($id), new CarTransformer())->toArray();
    }

    /**
     * Edit a Car
     *
     * @param $id
     * @return Car|Car[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return Car::find($id);
    }

    /**
     * Update a Car
     *
     * @param UpdateCarRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCarRequest $request, $id)
    {
        $car = Car::find($id);
        $car->update($request->validated());
        return redirect()->back()->with('successMessage', 'Car was successfully updated!');
    }

    /**
     * Delete a Car
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $car = Car::find($id);

            if(!$car->canSecureDelete('car')) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Car! Remove from all associations and try again!');
            }

            $car->secureDelete('car');
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('errorMessage', 'Unable to Delete Car . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Car was successfully deleted!');
    }
}

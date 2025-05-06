<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CarColorFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarColorRequest;
use App\Http\Requests\Admin\UpdateCarColorRequest;
use App\Models\CarColor;
use App\Transformers\Admin\CarColorTransformer;
use Inertia\Inertia;

class CarColorCrudController extends Controller
{
    /**
     * List all Car Colors
     *
     * @param CarColorFilters $filters
     * @return \Inertia\Response
     */
    public function index(CarColorFilters $filters)
    {
        return Inertia::render('Admin/CarColors', [
            'carColors' => function () use($filters) {
                return fractal(CarColor::filter($filters)
                    ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new CarColorTransformer())->toArray();
            },
        ]);
    }

    /**
     * Store a CarColor
     *
     * @param StoreCarColorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCarColorRequest $request)
    {
        CarColor::create($request->validated());
        return redirect()->back()->with('successMessage', 'Car Color was successfully added!');
    }

    /**
     * Show a CarColor
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(CarColor::find($id), new CarColorTransformer())->toArray();
    }

    /**
     * Edit a CarColor
     *
     * @param $id
     * @return CarColor|CarColor[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return CarColor::find($id);
    }

    /**
     * Update a CarColor
     *
     * @param UpdateCarColorRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCarColorRequest $request, $id)
    {
        $carColor = CarColor::find($id);
        $carColor->update($request->validated());
        return redirect()->back()->with('successMessage', 'Car Color was successfully updated!');
    }

    /**
     * Delete a CarColor
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $carColor = CarColor::find($id);
            $carColor->delete();
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('errorMessage', 'Unable to Delete Car Color . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Car Color was successfully deleted!');
    }
}

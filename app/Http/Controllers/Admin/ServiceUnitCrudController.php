<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ServiceUnitFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceUnitRequest;
use App\Http\Requests\Admin\UpdateServiceUnitRequest;
use App\Models\City;
use App\Models\ServiceUnit;
use App\Models\UserGroup;
use App\Transformers\Admin\ServiceUnitTransformer;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ServiceUnitCrudController extends Controller
{
    /**
     * List all ServiceUnits
     *
     * @param ServiceUnitFilters $filters
     * @return \Inertia\Response
     */
    public function index(ServiceUnitFilters $filters)
    {
        return Inertia::render('Admin/ServiceUnits', [
            'serviceUnits' => function () use ($filters) {
                return fractal(
                    ServiceUnit::filter($filters)
                        ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new ServiceUnitTransformer()
                )->toArray();
            },
            'cities' => City::select('id', 'name')->get()
        ]);
    }

    /**
     * Store a ServiceUnit
     *
     * @param StoreServiceUnitRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreServiceUnitRequest $request)
    {
        $serviceUnit = new ServiceUnit();
        $serviceUnit->first_name = $request['name'];
        $serviceUnit->last_name = $request['number_plate'];
        $serviceUnit->mobile = $request['mobile'];
        $serviceUnit->email = $request['email'];
        $serviceUnit->email_verified_at = now()->toDateTimeString();
        $serviceUnit->mobile_verified_at = now()->toDateTimeString();
        $serviceUnit->password = Hash::make($request['password']);
        $serviceUnit->city_id = $request['city_id'];
        $serviceUnit->is_active = $request['is_active'];
        $serviceUnit->role = 'service_unit';
        $serviceUnit->save();

        return redirect()->back()->with('successMessage', 'Service Unit was successfully added!');
    }

    /**
     * Show a ServiceUnit
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(ServiceUnit::find($id), new ServiceUnitTransformer())->toArray();
    }

    /**
     * Edit a ServiceUnit
     *
     * @param $id
     * @return ServiceUnit|ServiceUnit[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return ServiceUnit::find($id);
    }

    /**
     * Update a ServiceUnit
     *
     * @param UpdateServiceUnitRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateServiceUnitRequest $request, $id)
    {
        $serviceUnit = ServiceUnit::find($id);
        $serviceUnit->first_name = $request['name'];
        $serviceUnit->last_name = $request['number_plate'];
        $serviceUnit->mobile = $request['mobile'];
        $serviceUnit->email = $request['email'];
        $serviceUnit->city_id = $request['city_id'];
        $serviceUnit->is_active = $request['is_active'];
        if ($serviceUnit->isDirty('email')) {
            $serviceUnit->email_verified_at = now();
        }

        if ($serviceUnit->isDirty('mobile')) {
            $serviceUnit->mobile_verified_at = now();
        }

        if ($request->filled('password')) {
            $serviceUnit->password = Hash::make($request['password']);
        }

        $serviceUnit->save();
        return redirect()->back()->with('successMessage', 'Service Unit was successfully updated!');
    }

    /**
     * Delete a ServiceUnit
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $serviceUnit = ServiceUnit::find($id);

            if (!$serviceUnit->canSecureDelete('bookings')) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Service Unit! Remove from all associations and try again!');
            }

            $serviceUnit->secureDelete('bookings');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('errorMessage', 'Unable to Delete Service Unit . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'ServiceUnit was successfully deleted!');
    }
}

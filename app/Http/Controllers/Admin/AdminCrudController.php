<?php

namespace App\Http\Controllers\Admin;

use App\Filters\AdminFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\City;
use App\Models\Admin;
use App\Models\UserGroup;
use App\Transformers\Admin\AdminTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminCrudController extends Controller
{
    /**
     * List all Admins
     *
     * @param AdminFilters $filters
     * @return \Inertia\Response
     */
    public function index(AdminFilters $filters)
    {
        return Inertia::render('Admin/Admins', [
            'admins' => function () use($filters) {
                return fractal(Admin::with('city')->filter($filters)
                    ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new AdminTransformer())->toArray();
            },
            'cities' => City::select('id', 'name')->get()
        ]);
    }

    /**
     * Store a Admin
     *
     * @param StoreAdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAdminRequest $request)
    {
        $admin = new Admin();
        $admin->first_name = $request['first_name'];
        $admin->last_name = $request['last_name'];
        $admin->mobile = $request['mobile'];
        $admin->email = $request['email'];
        $admin->email_verified_at = now()->toDateTimeString();
        $admin->mobile_verified_at = now()->toDateTimeString();
        $admin->password = Hash::make($request['password']);
        $admin->city_id = $request['city_id'];
        $admin->is_active = $request['is_active'];
        $admin->role = 'admin';
        $admin->save();

        return redirect()->back()->with('successMessage', 'Admin was successfully added!');
    }

    /**
     * Show a Admin
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(Admin::find($id), new AdminTransformer())->toArray();
    }

    /**
     * Edit a Admin
     *
     * @param $id
     * @return Admin|Admin[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return Admin::find($id);
    }

    /**
     * Update a Admin
     *
     * @param UpdateAdminRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAdminRequest $request, $id)
    {
        $admin = Admin::find($id);
        $admin->first_name = $request['first_name'];
        $admin->last_name = $request['last_name'];
        $admin->mobile = $request['mobile'];
        $admin->email = $request['email'];
        $admin->email_verified_at = now()->toDateTimeString();
        $admin->mobile_verified_at = now()->toDateTimeString();
        $admin->city_id = $request['city_id'];
        $admin->is_active = $request['is_active'];

        if($request['password'] != null || $request['password'] != '') {
            $admin->password = Hash::make($request['password']);
        }

        $admin->update();

        return redirect()->back()->with('successMessage', 'Admin was successfully updated!');
    }

    /**
     * Delete a Admin
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $admin = Admin::find($id);

            $admin->secureDelete();
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('errorMessage', 'Unable to Delete Admin . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Admin was successfully deleted!');
    }
}

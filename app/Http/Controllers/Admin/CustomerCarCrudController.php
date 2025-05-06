<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CustomerCarFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerCarRequest;
use App\Http\Requests\Admin\UpdateCustomerCarRequest;
use App\Models\Car;
use App\Models\CarColor;
use App\Models\Customer;
use App\Models\CustomerCar;
use App\Transformers\Admin\CustomerCarSearchTransformer;
use App\Transformers\Admin\CustomerCarTransformer;
use App\Transformers\CarSearchTransformer;
use App\Transformers\ColorSearchTransformer;
use App\Transformers\CustomerSearchTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerCarCrudController extends Controller
{
    /**
     * List all CustomerCustomerCars
     *
     * @param CustomerCarFilters $filters
     * @return \Inertia\Response
     */
    public function index(CustomerCarFilters $filters)
    {
        $perPage = request()->perPage ?? 10;
        $customerCars = fn() => fractal(CustomerCar::with(['customer', 'car'])->filter($filters)->orderByDesc('created_at')->paginate($perPage), new CustomerCarTransformer())->toArray();
        $customers = fractal(Customer::select('id', 'first_name', 'last_name')->get(), new CustomerSearchTransformer())->toArray()['data'];
        $cars = fractal(Car::with('brand:id,name')->get(), new CarSearchTransformer())->toArray()['data'];
        $colors = fractal(CarColor::all(), new ColorSearchTransformer())->toArray()['data'];
        return Inertia::render('Admin/CustomerCars', [
            'customerCars' => $customerCars,
            'customers' => $customers,
            'cars' => $cars,
            'colors' => $colors,
        ]);
    }


    /**
     * Search customer cars
     *
     * @param Request $request
     * @param CustomerCarFilters $filters
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request, CustomerCarFilters $filters)
    {
        $query = $request->get('query');
        return response()->json(['customerCars' => fractal(CustomerCar::with('car')->where('number_plate', 'like', '%' . $query . '%')->orWhere('code', 'like', '%' . $query . '%')->filter($filters)->limit(20)->get(), new CustomerCarSearchTransformer())->toArray()['data']]);
    }

    /**
     * Store a CustomerCar
     *
     * @param StoreCustomerCarRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCustomerCarRequest $request)
    {
        CustomerCar::create($request->validated());
        return redirect()->back()->with('successMessage', 'CustomerCar was successfully added!');
    }

    /**
     * Show a CustomerCar
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(CustomerCar::find($id), new CustomerCarTransformer())->toArray();
    }

    /**
     * Edit a CustomerCar
     *
     * @param $id
     * @return CustomerCar|CustomerCar[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return CustomerCar::find($id);
    }

    /**
     * Update a CustomerCar
     *
     * @param UpdateCustomerCarRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCustomerCarRequest $request, $id)
    {
        $customerCar = CustomerCar::find($id);
        $customerCar->update($request->validated());
        return redirect()->back()->with('successMessage', 'CustomerCar was successfully updated!');
    }

    /**
     * Delete a CustomerCar
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $customerCar = CustomerCar::find($id);
            if (!$customerCar->canSecureDelete('bookings')) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete CustomerCar! Remove from all associations and try again!');
            }
            $customerCar->secureDelete('bookings');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('errorMessage', 'Unable to Delete CustomerCar . Remove all associations and Try again!');
        }
        return redirect()->back()->with('successMessage', 'CustomerCar was successfully deleted!');
    }
}

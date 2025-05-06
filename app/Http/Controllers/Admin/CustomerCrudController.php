<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CustomerFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerAddressRequest;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\City;
use App\Models\Customer;
use App\Models\ServiceUnit;
use App\Models\UserGroup;
use App\Repositories\BookingRepository;
use App\Transformers\Admin\CustomerSearchTransformer;
use App\Transformers\Admin\CustomerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class CustomerCrudController extends Controller
{
	/**
	 * List all Customers 
	 *
	 * @param CustomerFilters $filters
	 * @return \Inertia\Response
	 */
	public function index(CustomerFilters $filters)
	{
		return Inertia::render('Admin/Customers', [
			'customers' => function () use ($filters) {
				$customers = Customer::with(['city', 'wallet', 'transactions' => function ($query) {
					$query->orderByDesc('created_at');
				}])->orderByDesc('id')->filter($filters)->paginate(request()->perPage ?? 10);
				foreach ($customers as $customer) {
					if (!$customer->wallet) {
						$customer->wallet = (object) ['balance' => 0, 'transactions' => []];
					}
				}
				return fractal($customers, new CustomerTransformer())->paginateWith(new \League\Fractal\Pagination\IlluminatePaginatorAdapter($customers))->toArray();
			},
			'serviceUnits' => ServiceUnit::select('id', 'first_name as name')->get(),
			'userGroups' => UserGroup::select('id', 'name')->get(),
			'cities' => City::select('id', 'name')->get()
		]);
	}


	/**
	 * Search customers
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function search(Request $request)
	{
		$query = $request->get('query');
		return response()->json(['customers' => fractal(Customer::where('first_name', 'like', '%' . $query . '%')->orWhere('last_name', 'like', '%' . $query . '%')->limit(20)->get(), new CustomerSearchTransformer())->toArray()['data']]);
	}

	/**
	 * Store a Customer
	 *
	 * @param StoreCustomerRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(StoreCustomerRequest $request)
	{
		$customer = new Customer();
		$customer->first_name = $request['first_name'];
		$customer->last_name = $request['last_name'];
		$customer->mobile = $request['mobile'];
		$customer->email = $request['email'];
		$customer->email_verified_at = now()->toDateTimeString();
		$customer->mobile_verified_at = now()->toDateTimeString();
		$customer->password = Hash::make($request['password']);
		$customer->city_id = $request['city_id'];
		$customer->service_unit_id = $request['service_unit_id'];
		$customer->user_group_id = $request['user_group_id'];
		$customer->is_active = $request['is_active'];
		$customer->reference = $request['reference'];
		$customer->role = 'customer';
		if ($customer->save()) {
			$transactionType = $request['transaction_type'];
			$amount = (float) $request['wallet_transaction_amount'];
			if (in_array($transactionType, ['deposite', 'withdraw']) && $amount > 0) {
				$wallet = $customer->wallet ?? $customer->createWallet();
				$amountInCents = $amount * 100;
				$description = $request['wallet_transaction_note'] ?? ucfirst($transactionType) . ' by admin';
				if ($transactionType === 'deposite') {
					$wallet->deposit($amountInCents, [
						'description' => $description,
					]);
				} elseif ($transactionType === 'withdraw') {
					if ($wallet->canWithdraw($amountInCents)) {
						$wallet->withdraw($amountInCents, [
							'description' => $description,
						]);
					} else {
						return redirect()->back()->withErrors([
							'wallet_transaction_amount' => 'Insufficient wallet balance for this withdrawal.',
						]);
					}
				}
			} elseif ($transactionType && $amount <= 0) {
				return redirect()->back()->withErrors([
					'wallet_transaction_amount' => 'Transaction amount must be positive.',
				]);
			}
		}
		return redirect()->back()->with('successMessage', 'Customer was successfully added!');
	}

	/**
	 * Show a Customer
	 *
	 * @param $id
	 * @return array
	 */
	public function show($id)
	{
		return fractal(Customer::find($id), new CustomerTransformer())->toArray();
	}

	/**
	 * Edit a Customer
	 *
	 * @param $id
	 * @return Customer|Customer[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
	 */
	public function edit($id)
	{
		return Customer::with('wallet')->find($id);
	}

	/**
	 * Update a Customer
	 *
	 * @param UpdateCustomerRequest $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateCustomerRequest $request, $id)
	{
		$customer = Customer::find($id);
		$customer->first_name = $request['first_name'];
		$customer->last_name = $request['last_name'];
		$customer->mobile = $request['mobile'];
		$customer->email = $request['email'];
		$customer->email_verified_at = now()->toDateTimeString();
		$customer->mobile_verified_at = now()->toDateTimeString();
		$customer->city_id = $request['city_id'];
		$customer->service_unit_id = $request['service_unit_id'];
		$customer->user_group_id = $request['user_group_id'];
		$customer->reference = $request['reference'];
		$customer->is_active = $request['is_active'];
		if (!empty($request->password)) {
			$customer->password = Hash::make($request->password);
		}
		$customer->save();
		$transactionType = $request['transaction_type'];
		$amount = (float) $request['wallet_transaction_amount'];
		if (in_array($transactionType, ['deposite', 'withdraw']) && $amount > 0) {
			$wallet = $customer->wallet ?? $customer->createWallet();
			$amountInCents = $amount * 100;
			$description = $request['wallet_transaction_note'] ?? ucfirst($transactionType) . ' by admin';
			if ($transactionType === 'deposite') {
				$wallet->deposit($amountInCents, [
					'description' => $description,
				]);
			} elseif ($transactionType === 'withdraw') {
				if ($wallet->canWithdraw($amountInCents)) {
					$wallet->withdraw($amountInCents, [
						'description' => $description,
					]);
				} else {
					return redirect()->back()->withErrors([
						'wallet_transaction_amount' => 'Insufficient wallet balance for this withdrawal.',
					]);
				}
			}
		} elseif ($transactionType && $amount <= 0) {
			return redirect()->back()->withErrors([
				'wallet_transaction_amount' => 'Transaction amount must be positive.',
			]);
		}
		return redirect()->back()->with('successMessage', 'Customer was successfully updated!');
	}

	/**
	 * Get customer address
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function address($id, BookingRepository $bookingRepository)
	{
		$customer = Customer::find($id);
		$address = $customer->preferences->get('address', [
			'address' =>  '',
			'area' =>  '',
			'latitude' =>  '',
			'longitude' =>  '',
			'address_type' => 'flat',
			'house_no' =>  '',
			'house_name' =>  '',
			'city_id' =>  null,
			'approved' => false,
		]);
		$day_time_list_array = json_decode($customer->day_time_list, true);
		if (isset($day_time_list_array) && $day_time_list_array !== 'null') {
			$address['day_time_list'] = $day_time_list_array;
		} else {
			$day_time_list = $bookingRepository->bulkWashPreferences();
			$address['day_time_list'] = $day_time_list['days'];
		}
		return $address;
	}

	/**
	 * Update customer address
	 *
	 * @param CustomerAddressRequest $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateAddress(CustomerAddressRequest $request, $id)
	{
		$customer = Customer::find($id);
		$req = 	$request->all();
		$list = $req['day_time_list'];
		/*  foreach ($list as $lists) {
				if(isset($lists['selected']) && !empty($lists['selected']))
				{
					$days[] = $lists; 
					
				}
			}  */
		$customer->preferences->set([
			'address' => $request->validated()
		]);
		$customer->day_time_list = $list;

		$customer->save();
		return redirect()->back()->with('successMessage', 'Customer Address was successfully updated!');
	}

	/**
	 * Delete a Customer
	 *
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id)
	{
		try {
			$customer = Customer::find($id);
			if (!$customer->canSecureDelete('bookings', 'invoices', 'subscriptions', 'cars')) {
				return redirect()->back()->with('errorMessage', 'Unable to Delete Customer! Remove from all associations and try again!');
			}
			$customer->secureDelete('bookings', 'invoices', 'subscriptions', 'cars');
		} catch (\Illuminate\Database\QueryException $e) {
			return redirect()->back()->with('errorMessage', 'Unable to Delete Customer . Remove all associations and Try again!');
		}
		return redirect()->back()->with('successMessage', 'Customer was successfully deleted!');
	}
}

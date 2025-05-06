<?php


namespace App\Http\Controllers\Api;

use App\Transformers\Api\CustomerAddressTransformer;
use App\Transformers\Api\CustomerCarTransformer;
use App\Models\CustomerCar;
use App\Models\Customer;
use App\Models\User;
use App\Transformers\Api\CustomerTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController
{
	public function getProfile()
	{
		$data = fractal(request()->user(), new CustomerTransformer())->toArray();
		return response()->json($data["data"], 200);
	}

	public function updateProfile(Request $request)
	{
		$customer = $request->user();
		$validator = Validator::make($request->all(), [
			'device_name' => ['required'],
			'first_name' => ['required'],
			'alt_mobile' => ['nullable', function ($attribute, $value, $fail) use ($customer) {
				if ($value == $customer->mobile) {
					$fail('Alternative mobile number should not be same as primary number.');
				}
			}],
		]);
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()]);
		}
		$customer->first_name = $request->get('first_name');
		$customer->alt_mobile = $request->get('alt_mobile');
		$customer->update();
		$customerData = fractal($customer, new CustomerTransformer())->toArray();
		return response()->json(['message' => 'success', 'user' => $customerData["data"], 'token' => $customer->createToken($request->device_name)->plainTextToken], 200);
	}



	public function status_update(Request $request)
	{
		$customer = $request->user();
		$validator = Validator::make($request->all(), [
			'is_active' => ['required'],
			'device_name' => ['required']
		]);
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()]);
		}
		$customer->is_active = $request->get('is_active');
		$customer->update();
		$customerData = fractal($customer, new CustomerTransformer())->toArray();
		return response()->json(['message' => 'success', 'user' => $customerData["data"], 'token' => $customer->createToken($request->device_name)->plainTextToken], 200);
	}



	public function updateLocation(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'device_name' => 'required',
			'address_type' => 'required',
			'flat_no' => 'required',
			'flat_name' => 'required',
			'address' => 'required',
			'area' => 'required',
			'latitude' => 'required',
			'longitude' => 'required',
		]);
		$customer = request()->user();
		$address = $customer->preferences->get('address', null);
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()]);
		}
		if ($address) {
			if ($address['approved']) {
				$customer->addressChangeRequests()->create($request->except('device_name'));
			} else {
				$customer->preferences->set([
					'address' => [
						'address' =>  $request->address,
						'area' =>  $request->area,
						'latitude' =>  $request->latitude,
						'longitude' =>  $request->longitude,
						'address_type' => $request->address_type,
						'house_no' =>  $request->flat_no,
						'house_name' =>  $request->flat_name,
						'approved' => false,
					]
				]);
				$customer->update();
			}
		} else {
			$customer->preferences->set([
				'address' => [
					'address' =>  $request->address,
					'area' =>  $request->area,
					'latitude' =>  $request->latitude,
					'longitude' =>  $request->longitude,
					'address_type' => $request->flat,
					'house_no' =>  $request->flat_no,
					'house_name' =>  $request->flat_name,
					'approved' => false,
				]
			]);
			$customer->update();
		}
		$customerData = fractal($customer, new CustomerTransformer())->toArray();
		return response()->json(['message' => 'success', 'user' => $customerData["data"], 'token' => $customer->createToken($request->device_name)->plainTextToken], 200);
	}

	public function getLocation(Request $request)
	{
		$customer = request()->user();
		$address = $customer->preferences->get('address', null);
		if (!$address) {
			return response()->json([], 200);
		}
		return response()->json([
			'address_type'       => (string) $address['address_type'],
			'flat_no'       => (string) $address['house_no'],
			'flat_name'       => (string) $address['house_name'],
			'address'       => (string) $address['address'],
			'area'       => (string) $address['area'],
			'latitude'       => (string) $address['latitude'],
			'longitude'       => (string) $address['longitude'],
			'is_verified'       => (bool) $address['approved'],
		], 200);
	}

	public function addCar(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'car_id' => 'required|numeric',
			'color' => 'required|max:60',
			'number_plate' => 'required|max:60|unique:customer_cars',
		]);
		if ($validator->fails()) {
			return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
		}
		try {
			$customer = request()->user();
			$customer->cars()->create([
				'car_id' => $request->car_id,
				'number_plate' => $request->number_plate,
				'color' => $request->color
			]);
		} catch (\Exception $exception) {
			return response()->json(['success' => false, 'message' => "Something Went Wrong. Please try again."]);
		}
		return response()->json(['success' => true, "message" => "Car Successfully Added."], 200);
	}

	public function removeCar($id)
	{
		try {
			$car = CustomerCar::findOrFail($id);
			if (!$car->canSecureDelete('subscriptions', 'invoices', 'bookings', 'schedules')) {
				return response()->json([
					'success' => false,
					'message' => "Can't Delete Car. Invoices/Bookings exist for this car."
				], 403);
			}
			$car->secureDelete('subscriptions', 'invoices', 'bookings', 'schedules');
			return response()->json([
				'success' => true,
				"message" => "Car Successfully Deleted."
			], 200);
		} catch (ModelNotFoundException $ex) {
			return response()->json([
				'success' => false,
				'message' => 'Entry for ' . str_replace('App\Models\\', '', $ex->getModel()) . ' not found.'
			]);
		} catch (\Exception $exception) {
			return response()->json([
				'success' => false,
				'message' => "Something Went Wrong. Please try again."
			]);
		}
	}

	public function getMyCars(Request $request)
	{
		$customer = request()->user();
		if ($request->has('car_type')) {
			$cars = $customer->active()->with('car')->whereHas('car', function ($query) use ($request) {
				$query->where('car_type_id', $request->car_type);
			})->get();
		} else {
			$cars = $customer->cars()->with('car')->get();
		}
		$data = fractal($cars, new CustomerCarTransformer())->toArray();
		return response()->json($data["data"], 200);
	}

	public function getWalletDetails()
	{
		$customer = request()->user();
		return response()->json(['balance' => $customer->balanceFloat], 200);
	}

	public function getDetail($id)
	{
		$user = User::find($id)->toArray();
		$preferences = json_decode($user['preferences']);
		$unseetll = $preferences->address;
		$unseetll = (array)$unseetll;
		unset($unseetll['dayCollection']);
		unset($user['preferences']);
		$user['preferences']['address'] = json_encode($unseetll);
		if (is_null($user)) {
			$data = [
				'message' => 'User not found',
				'status' => '0',
			];
		} else {
			$data = [
				'message' => 'User found',
				'status' => '1',
				'data' => $user
			];
		}
		return $data["data"];
	}

	public function getDaylist($id)
	{
		$users = User::find($id);
		$user = $users['day_time_list'];
		$data = json_decode($user, true);
		$selectedDays[] = '';
		$selectedDays = array_filter($data, function ($day) {
			return isset($day['selected']) && $day['selected'] === true;
		});
		$selectedDays = array_values($selectedDays);
		$text = 'No any day is selected by this user';
		$selectedDays = (!empty($selectedDays)) ? $selectedDays : $text;
		$jsonResult = json_encode($selectedDays);
		if (is_null($user)) {
			$data = [
				'message' => 'User not found',
				'status' => '0',
			];
		} else {
			$data = [
				'message' => 'User found',
				'status' => '1',
				'data' => $jsonResult
			];
		}
		return $data["data"];
	}
}

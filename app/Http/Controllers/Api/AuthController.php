<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Transformers\Api\CustomerTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController
{
	/**
	 * Register User
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'mobile' => ['required', 'numeric', 'unique:users'],
			'password' => ['required', 'string', 'min:8'],
			'device_name' => ['required', 'string'],
			'city_id' => ['required']
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 422);
		}
		$request->request->add(['first_name' => $request->name]);
		$user = Customer::create($request->except(['name', 'device_name']));
		$customer = fractal($user, new CustomerTransformer())->toArray();
		return response()->json([
			'success' => true,
			'user' => $customer['data'],
			'token' => $user->createToken($request->device_name)->plainTextToken
		], 200);
	}

	public function sendVerificationCode(Request $request, CustomerRepository $repository)
	{
		$validator = Validator::make($request->all(), [
			'mobile' => ['required', 'string', 'min:10']
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 422);
		}
		$customer = Customer::where('mobile', $request->mobile)->firstOrFail();
		if ($repository->sendVerificationCode($customer, 6)) {
			return response()->json([
				'success' => true,
				'message' => 'Code Sent Successfully'
			], 200);
		}
		return response()->json(['error' => 'Unable to send verification code'], 422);
	}

	public function verifyMobile(Request $request, CustomerRepository $repository)
	{
		$validator = Validator::make($request->all(), [
			'mobile' => ['required', 'string', 'min:10'],
			'code' => ['required'],
			'device_name' => ['required', 'string']
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 422);
		}
		$user = Customer::where('mobile', $request->mobile)->firstOrFail();
		$otpVerified = $repository->verifyCode($user, $request->code);
		if (!$user || !$otpVerified) {
			return response()->json(['error' => 'Invalid User/Verification Code.'], 422);
		}
		$user->tokens()->delete();
		$customer = fractal($user, new CustomerTransformer())->toArray();
		return response()->json([
			'success' => true,
			'user' => $customer['data'],
			'token' => $user->createToken($request->device_name)->plainTextToken
		], 200);
	}

	/**
	 * User Login
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => ['required', 'string', 'email', 'max:255'],
			'password' => ['required', 'string', 'min:8'],
			'device_name' => ['required', 'string']
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 422);
		}
		$user = Customer::where('email', $request->email)->where('is_active', 1)->first();
		if (empty($user)) {
			return response()->json(['error' => 'The provided email is not registered.'], 420);
		} else {
			if (!Hash::check($request->password, $user->password)) {
				return response()->json(['error' => 'The provided credentials are incorrect.'], 422);
			}
			$customer = fractal($user, new CustomerTransformer())->toArray();
			return response()->json([
				'success' => true,
				'user' => $customer['data'],
				'token' => $user->createToken($request->device_name)->plainTextToken
			], 200);
		}
	}

	public function sendLoginOTP(Request $request, CustomerRepository $repository)
	{
		$validator = Validator::make($request->all(), [
			'mobile' => ['required', 'string', 'min:10']
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 422);
		}
		try {
			$customer = Customer::where('mobile', $request->mobile)->firstOrFail();
			if ($repository->sendOtp($customer, 4)) {
				return response()->json([
					'success' => true,
					'message' => 'OTP Sent Successfully'
				], 200);
			}
		} catch (\Exception $exception) {
			return response()->json(['error' => 'Unable to send verification code'], 422);
		}
		return response()->json(['error' => 'Unable to send verification code'], 422);
	}

	public function loginWithOTP(Request $request, CustomerRepository $repository)
	{
		$validator = Validator::make($request->all(), [
			'code' => ['required'],
			'mobile' => ['required', 'string', 'min:10'],
			'device_name' => ['required', 'string']
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 422);
		}
		$user = Customer::where('mobile', $request->mobile)->first();
		$otpVerified = $repository->verifyCode($user, $request->code);
		if (!$user || !$otpVerified) {
			return response()->json(['error' => 'Invalid User/OTP.'], 422);
		}
		$user->tokens()->delete();
		$customer = fractal($user, new CustomerTransformer())->toArray();
		return response()->json([
			'success' => true,
			'user' => $customer['data'],
			'token' => $user->createToken($request->device_name)->plainTextToken
		], 200);
	}

	/**
	 * Get logged in User
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getUser(Request $request)
	{
		$data = fractal($request->user(), new CustomerTransformer())->toArray();
		return response()->json($data["data"], 200);
	}

	/**
	 * Save FCM Tokens
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function saveFcmToken(Request $request)
	{
		$tokens = $request->user()->preferences->get('fcm_tokens', []);
		if (!in_array($request->token, $tokens, true)) {
			array_push($tokens, $request->token);
		}
		$request->user()->preferences->set('fcm_tokens', $tokens);
		$request->user()->save();
		return response()->json(['success' => true], 200);
	}

	/**
	 * User Logout
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout(Request $request)
	{
		$request->user()->tokens()->delete();
		return response()->json(["message" => "Successfully Logout"], 200);
	}

	/**
	 * Unauthenticated fallback
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function unauthenticated()
	{
		return response()->json(["status" => "unauthenticated"], 403);
	}
}

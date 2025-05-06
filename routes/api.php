<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceUnitController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\HomeScreenController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PricingController;
//use App\Http\Controllers\WebHookController;

/*
	|--------------------------------------------------------------------------
	| API Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register API routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| is assigned the "api" middleware group. Enjoy building your API!
	|
*/
/*customer detail*/

Route::get('get_detail/{id}', [CustomerController::class, 'getDetail']);
Route::get('get_daylist/{id}', [CustomerController::class, 'getDaylist']);

/*customer detail*/
Route::prefix('v1')->group(function () {
	Route::get('/', [AppController::class, 'index'])->name('api_version');
	Route::get('/settings', [AppController::class, 'settings'])->name('app_settings');

	/*
		|--------------------------------------------------------------------------
		| Auth Routes
		|--------------------------------------------------------------------------
	*/
	Route::post('register', [AuthController::class, 'register']);
	Route::post('login', [AuthController::class, 'login']);
	Route::post('su/login', [ServiceUnitController::class, 'login']);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
	/*
		|--------------------------------------------------------------------------
		| Auth Routes
		|--------------------------------------------------------------------------
	*/
	Route::post('logout', [AuthController::class, 'logout']);
	Route::get('/user', [AuthController::class, 'getUser']);
	Route::post('/save-fcm-token', [AuthController::class, 'saveFcmToken']);

	/*
		|--------------------------------------------------------------------------
		| Car Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('/search_car', [CarController::class, 'searchCar']);
	Route::post('/add_car', [CustomerController::class, 'addCar']);
	Route::delete('/remove_customer_car/{id}', [CustomerController::class, 'removeCar']);
	Route::get('/get_my_cars', [CustomerController::class, 'getMyCars']);
	Route::get('/get_color_variants', [CarController::class, 'getColorVariants']);

	/*
		|--------------------------------------------------------------------------
		| Customer Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('get_profile', [CustomerController::class, 'getProfile']);
	Route::patch('update_profile', [CustomerController::class, 'updateProfile']);
	Route::post('update_location', [CustomerController::class, 'updateLocation']);
	Route::get('get_location', [CustomerController::class, 'getLocation']);
	Route::get('/fetch_wallet', [CustomerController::class, 'getWalletDetails']);

	Route::post('update_status_active', [CustomerController::class, 'status_update']);

	/*
		|--------------------------------------------------------------------------
		| Schedule Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('/get_customer_schedules', [ScheduleController::class, 'getCustomerSchedules']);

	/*
		|--------------------------------------------------------------------------
		| Pricing Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('/popular_plans', [PlanController::class, 'popularPlans']);
	Route::get('/popular_products', [ProductController::class, 'popularProducts']);

	Route::get('/get_car_products/{id}', [PricingController::class, 'getCarProducts']);
	Route::get('/get_car_plans/{id}', [PricingController::class, 'getCarPlans']);
	Route::post('/get_estimate', [PricingController::class, 'getEstimate']);

	/*
		|--------------------------------------------------------------------------
		| Subscription Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('/get_subscriptions', [SubscriptionController::class, 'getSubscriptions']);
	Route::get('/subscription_history', [SubscriptionController::class, 'subscriptionHistory']);

	/*
		|--------------------------------------------------------------------------
		| Order & Invoice Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('/get_orders', [OrderController::class, 'getOrders']);
	Route::get('/get_invoices', [OrderController::class, 'getInvoices']);

	Route::post('/create_fetch_order', [PaymentController::class, 'createOrder']);
	Route::post('/verify_update_order', [PaymentController::class, 'verifyUpdateOrder']);

	Route::post('/create_authenticate_subscription', [PaymentController::class, 'createAuthenticateSubscription']);
	Route::post('/verify_update_subscription', [PaymentController::class, 'verifyUpdateSubscription']);

	/*
		|--------------------------------------------------------------------------
		| Service Unit App Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('su/bookings', [ServiceUnitController::class, 'getBookings']);
	Route::post('su/update_wash_status', [ServiceUnitController::class, 'updateWash']);
	Route::post('su/update_interior_status', [ServiceUnitController::class, 'updateInterior']);
});


Route::prefix('v1')->group(function () {
	Route::get('/get_cities', [HomeScreenController::class, 'getCities']);
	Route::get('/get_banners', [HomeScreenController::class, 'getBanners']);

	Route::post('/send-verification-code', [AuthController::class, 'sendVerificationCode']);
	Route::post('/verify-mobile', [AuthController::class, 'verifyMobile']);
	Route::post('/send-login-code', [AuthController::class, 'sendLoginOTP']);
	Route::post('/login-with-otp', [AuthController::class, 'loginWithOTP']);
	Route::post('/unauthenticated', [AuthController::class, 'unauthenticated'])->name("unauthenticated");
	/*
		|--------------------------------------------------------------------------
		| Webhook Routes
		|--------------------------------------------------------------------------
	*/
	// Route::post('/invoke-web-hook', [WebHookController::class, 'webHook']);
});

Route::fallback(function () {
	return response()->json([
		'success' => false,
		'message' => 'Unauthorized/Invalid Api Call. If error persists, contact administrator.'
	], 404);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
	AppController,
	AuthController,
	ServiceUnitController,
	CarController,
	CustomerController,
	ScheduleController,
	PlanController,
	ProductController,
	OrderController,
	SubscriptionController,
	HomeScreenController,
	PaymentController,
	PricingController,
	NotificationController,
	ServiceComplaintController
};

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
// Public routes outside versioning (optional - move if needed)
Route::get('get_detail/{id}', [CustomerController::class, 'getDetail']);
Route::get('get_daylist/{id}', [CustomerController::class, 'getDaylist']);

// v1 public routes (unauthenticated)
Route::prefix('v1')->group(function () {

	// App base routes
	Route::get('/', [AppController::class, 'index'])->name('api_version');
	Route::get('/settings', [AppController::class, 'settings'])->name('app_settings');

	// Auth routes
	Route::post('register', [AuthController::class, 'register']);
	Route::post('login', [AuthController::class, 'login']);
	Route::post('su/login', [ServiceUnitController::class, 'login']);

	// Home screen
	Route::get('/get_cities', [HomeScreenController::class, 'getCities']);
	Route::get('/get_banners', [HomeScreenController::class, 'getBanners']);

	// Mobile verification
	Route::post('/send-verification-code', [AuthController::class, 'sendVerificationCode']);
	Route::post('/verify-mobile', [AuthController::class, 'verifyMobile']);
	Route::post('/send-login-code', [AuthController::class, 'sendLoginOTP']);
	Route::post('/login-with-otp', [AuthController::class, 'loginWithOTP']);
	Route::post('/unauthenticated', [AuthController::class, 'unauthenticated'])->name('unauthenticated');
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

	// Auth
	Route::post('logout', [AuthController::class, 'logout']);
	Route::get('/user', [AuthController::class, 'getUser']);
	Route::post('/save-fcm-token', [AuthController::class, 'saveFcmToken']);

	// Car
	Route::get('/search_car', [CarController::class, 'searchCar']);
	Route::get('/get_color_variants', [CarController::class, 'getColorVariants']);
	Route::post('/add_car', [CustomerController::class, 'addCar']);
	Route::delete('/remove_customer_car/{id}', [CustomerController::class, 'removeCar']);
	Route::get('/get_my_cars', [CustomerController::class, 'getMyCars']);

	// Customer
	Route::get('/get_profile', [CustomerController::class, 'getProfile']);
	Route::patch('/update_profile', [CustomerController::class, 'updateProfile']);
	Route::post('/update_location', [CustomerController::class, 'updateLocation']);
	Route::get('/get_location', [CustomerController::class, 'getLocation']);
	Route::get('/fetch_wallet', [CustomerController::class, 'getWalletDetails']);

	Route::post('update_status_active', [CustomerController::class, 'status_update']);

	// Schedule
	Route::get('/get_customer_schedules', [ScheduleController::class, 'getCustomerSchedules']);

	// Pricing
	Route::get('/popular_plans', [PlanController::class, 'popularPlans']);
	Route::get('/popular_products', [ProductController::class, 'popularProducts']);
	Route::get('/get_car_products/{id}', [PricingController::class, 'getCarProducts']);
	Route::get('/get_car_plans/{id}', [PricingController::class, 'getCarPlans']);
	Route::post('/get_estimate', [PricingController::class, 'getEstimate']);

	// Subscriptions
	Route::get('/get_subscriptions', [SubscriptionController::class, 'getSubscriptions']);
	Route::get('/subscription_history', [SubscriptionController::class, 'subscriptionHistory']);

	// Orders & Payments
	Route::get('/get_orders', [OrderController::class, 'getOrders']);
	Route::get('/get_invoices', [OrderController::class, 'getInvoices']);
	Route::post('/create_fetch_order', [PaymentController::class, 'createOrder']);
	Route::post('/verify_update_order', [PaymentController::class, 'verifyUpdateOrder']);
	Route::post('/create_authenticate_subscription', [PaymentController::class, 'createAuthenticateSubscription']);
	Route::post('/verify_update_subscription', [PaymentController::class, 'verifyUpdateSubscription']);

	// Notifications
	Route::get('/notifications', [NotificationController::class, 'index'])->name('customer.notifications');
	Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('customer.notifications.mark_as_read');
	Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('customer.notifications.mark_all_as_read');
	Route::delete('/notifications/delete/{id}', [NotificationController::class, 'deleteNotification'])->name('customer.notifications.delete');
	Route::delete('/notifications/delete_all', [NotificationController::class, 'deleteAllNotifications'])->name('customer.notifications.delete_all');

	// Service Unit
	Route::get('su/schedules', [ServiceUnitController::class, 'getSchedules']);
	Route::post('su/update_schedule_status', [ServiceUnitController::class, 'updateSchedule']);
	Route::post('su/update_item_status', [ServiceUnitController::class, 'updateItem']);

	Route::get('su/bookings', [ServiceUnitController::class, 'getBookings']);
	Route::post('su/update_wash_status', [ServiceUnitController::class, 'updateWash']);
	Route::post('su/update_interior_status', [ServiceUnitController::class, 'updateInterior']);
	Route::post('su/service_records', [ServiceUnitController::class, 'serviceRecords']);
	Route::get('su/car_service_records', [ServiceUnitController::class, 'carServiceRecords']);

	// Service Complaint
	Route::post('create_complaint', [ServiceComplaintController::class, 'storeComplaint']);
	Route::post('update_complaint/{id}', [ServiceComplaintController::class, 'updateComplaint']);
	Route::get('view_complaint/{id}', [ServiceComplaintController::class, 'viewComplaint']);
	Route::get('get_complaint', [ServiceComplaintController::class, 'getComplaint']);

	Route::get('su/view_complaint/{id}', [ServiceComplaintController::class, 'viewServiceUnitComplaint']);
	Route::get('su/get_complaint', [ServiceComplaintController::class, 'getServiceUnitComplaint']);
	Route::patch('su/update_complaint_status/{id}', [ServiceComplaintController::class, 'updateServiceUnitComplaintStatus']);
});

Route::fallback(function () {
	return response()->json([
		'success' => false,
		'message' => 'Unauthorized/Invalid Api Call. If error persists, contact administrator.'
	], 404);
});

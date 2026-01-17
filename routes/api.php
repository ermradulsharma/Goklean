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
| is assigned the "api" middleware group.
|
*/

// --- Legacy / Non-Versioned Public Routes ---
// Renamed for consistency but keeping ID structure
Route::get('customers/detail/{id}', [CustomerController::class, 'getDetail']);     // old: get_detail/{id}
Route::get('customers/daylist/{id}', [CustomerController::class, 'getDaylist']);   // old: get_daylist/{id}


// --- V1 Public Routes (Unauthenticated) ---
Route::prefix('v1')->group(function () {

	// App & Settings
	Route::get('/', [AppController::class, 'index'])->name('api_version');
	Route::get('/settings', [AppController::class, 'settings'])->name('app_settings');

	// Home Screen Data
	Route::get('/cities', [HomeScreenController::class, 'getCities']);      // old: get_cities
	Route::get('/banners', [HomeScreenController::class, 'getBanners']);    // old: get_banners

	// Authentication & Registration
	Route::post('register', [AuthController::class, 'register']);
	Route::post('login', [AuthController::class, 'login']);
	Route::post('su/login', [ServiceUnitController::class, 'login']);

	// OTP / Mobile Verification
	Route::post('/send-verification-code', [AuthController::class, 'sendVerificationCode']);
	Route::post('/verify-mobile', [AuthController::class, 'verifyMobile']);
	Route::post('/send-login-code', [AuthController::class, 'sendLoginOTP']);
	Route::post('/login-with-otp', [AuthController::class, 'loginWithOTP']);

	// Auth Fallback
	Route::post('/unauthenticated', [AuthController::class, 'unauthenticated'])->name('unauthenticated');
});


// --- V1 Protected Routes (Authenticated) ---
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

	// Auth Actions
	Route::post('logout', [AuthController::class, 'logout']);
	Route::get('/user', [AuthController::class, 'getUser']);
	Route::post('/save-fcm-token', [AuthController::class, 'saveFcmToken']);

	// --- Customer Module ---
	Route::get('/profile', [CustomerController::class, 'getProfile']);            // old: get_profile
	Route::patch('/profile/update', [CustomerController::class, 'updateProfile']); // old: update_profile
	Route::post('/profile/update-location', [CustomerController::class, 'updateLocation']); // old: update_location
	Route::get('/profile/location', [CustomerController::class, 'getLocation']);    // old: get_location
	Route::get('/wallet', [CustomerController::class, 'getWalletDetails']);         // old: fetch_wallet
	Route::post('/profile/update-status', [CustomerController::class, 'status_update']); // old: update_status_active

	// --- Vehicle Management ---
	Route::get('/cars/search', [CarController::class, 'searchCar']);                // old: search_car
	Route::get('/cars/colors', [CarController::class, 'getColorVariants']);         // old: get_color_variants
	Route::get('/my-cars', [CustomerController::class, 'getMyCars']);               // old: get_my_cars
	Route::post('/cars/add', [CustomerController::class, 'addCar']);                // old: add_car
	Route::delete('/cars/{id}', [CustomerController::class, 'removeCar']);          // old: remove_customer_car/{id}

	// --- Operations (Bookings & Schedules) ---
	Route::get('/schedules', [ScheduleController::class, 'getCustomerSchedules']);  // old: get_customer_schedules

	// --- Finance & Billing ---
	// Products & Plans
	Route::get('/plans/popular', [PlanController::class, 'popularPlans']);          // old: popular_plans
	Route::get('/products/popular', [ProductController::class, 'popularProducts']); // old: popular_products
	Route::get('/cars/{id}/products', [PricingController::class, 'getCarProducts']); // old: get_car_products/{id}
	Route::get('/cars/{id}/plans', [PricingController::class, 'getCarPlans']);       // old: get_car_plans/{id}
	Route::post('/estimate', [PricingController::class, 'getEstimate']);            // old: get_estimate

	// Subscriptions
	Route::get('/subscriptions', [SubscriptionController::class, 'getSubscriptions']); // old: get_subscriptions
	Route::get('/subscriptions/history', [SubscriptionController::class, 'subscriptionHistory']); // old: subscription_history

	// Orders & Payments
	Route::get('/orders', [OrderController::class, 'getOrders']);                   // old: get_orders
	Route::get('/invoices', [OrderController::class, 'getInvoices']);               // old: get_invoices
	Route::post('/orders/create-fetch', [PaymentController::class, 'createOrder']); // old: create_fetch_order
	Route::post('/orders/verify', [PaymentController::class, 'verifyUpdateOrder']); // old: verify_update_order
	Route::post('/subscriptions/create-authenticate', [PaymentController::class, 'createAuthenticateSubscription']); // old: create_authenticate_subscription
	Route::post('/subscriptions/verify', [PaymentController::class, 'verifyUpdateSubscription']); // old: verify_update_subscription

	// --- Notifications ---
	Route::get('/notifications', [NotificationController::class, 'index'])->name('customer.notifications');
	Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('customer.notifications.mark_as_read');
	Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('customer.notifications.mark_all_as_read');
	Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('customer.notifications.delete'); // old: notifications/delete/{id}
	Route::delete('/notifications', [NotificationController::class, 'deleteAllNotifications'])->name('customer.notifications.delete_all'); // old: notifications/delete_all

	// --- Service Complaints ---
	Route::get('/complaints', [ServiceComplaintController::class, 'getComplaint']); // old: get_complaint
	Route::post('/complaints', [ServiceComplaintController::class, 'storeComplaint']); // old: create_complaint
	Route::get('/complaints/{id}', [ServiceComplaintController::class, 'viewComplaint']); // old: view_complaint/{id}
	Route::post('/complaints/{id}', [ServiceComplaintController::class, 'updateComplaint']); // old: update_complaint/{id}

	// --- Service Unit Module (SU) ---
	Route::prefix('su')->group(function () {
		Route::get('schedules', [ServiceUnitController::class, 'getSchedules']);
		Route::get('bookings', [ServiceUnitController::class, 'getBookings']);

		// Status Updates
		Route::post('schedules/status', [ServiceUnitController::class, 'updateSchedule']); // old: update_schedule_status
		Route::post('items/status', [ServiceUnitController::class, 'updateItem']);         // old: update_item_status
		Route::post('wash/status', [ServiceUnitController::class, 'updateWash']);          // old: update_wash_status
		Route::post('interior/status', [ServiceUnitController::class, 'updateInterior']);  // old: update_interior_status

		// Records
		Route::post('service-records', [ServiceUnitController::class, 'serviceRecords']);  // old: service_records
		Route::get('service-records/cars', [ServiceUnitController::class, 'carServiceRecords']); // old: car_service_records

		// SU Complaints
		Route::get('complaints', [ServiceComplaintController::class, 'getServiceUnitComplaint']); // old: get_complaint
		Route::get('complaints/{id}', [ServiceComplaintController::class, 'viewServiceUnitComplaint']); // old: view_complaint/{id}
		Route::patch('complaints/{id}/status', [ServiceComplaintController::class, 'updateServiceUnitComplaintStatus']); // old: update_complaint_status/{id}
	});
});

// --- Fallback ---
Route::fallback(function () {
	return response()->json([
		'success' => false,
		'message' => 'Unauthorized/Invalid Api Call. If error persists, contact administrator.'
	], 404);
});

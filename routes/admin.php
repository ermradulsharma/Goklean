<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Admin\BannerCrudController;
use App\Http\Controllers\Admin\BasePriceCrudController;
use App\Http\Controllers\Admin\BookingCrudController;
use App\Http\Controllers\Admin\CarCrudController;
use App\Http\Controllers\Admin\CustomerCarCrudController;
use App\Http\Controllers\Admin\DiscountCrudController;
use App\Http\Controllers\Admin\InvoiceCrudController;
use App\Http\Controllers\Admin\PlanCrudController;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SubscriptionCrudController;
use App\Http\Controllers\Admin\UserGroupCrudController;
use App\Http\Controllers\Admin\CarColorCrudController;
use App\Http\Controllers\Admin\CarBrandCrudController;
use App\Http\Controllers\Admin\CustomerCrudController;
use App\Http\Controllers\Admin\ServiceUnitCrudController;
use App\Http\Controllers\Admin\AdminCrudController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\ChangeRequestController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ScheduleCrudController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ReferralController;
use App\Http\Controllers\Admin\ExportCsv;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;


/*
		|--------------------------------------------------------------------------
		| Admin Web Routes
		|--------------------------------------------------------------------------
		|
		| Here is where you can register admin web routes for your application.
		| These routes are loaded by the RouteServiceProvider within a group which
		| contains the "web" middleware group. Now create something great!
		|
	*/

Route::middleware(['auth:sanctum', 'verified'])->prefix('admin')->group(function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

	/*
		|--------------------------------------------------------------------------
		| Booking Routes
		|--------------------------------------------------------------------------
	*/

	Route::resource('bookings', BookingCrudController::class);
	Route::resource('schedules', ScheduleCrudController::class);

	/*
		|--------------------------------------------------------------------------
		| Refund Routes
		|--------------------------------------------------------------------------
	*/

	Route::get('refunds', [RefundController::class, 'index'])->name('refunds.index');
	Route::get('calculate-refund/{id}', [RefundController::class, 'calculate'])->name('refunds.calculate');
	Route::post('initiate-refund/{id}', [RefundController::class, 'initiate'])->name('refunds.initiate');
	Route::post('approve-refund/{id}', [RefundController::class, 'approve'])->name('refunds.approve');
	Route::delete('refunds/{id}', [RefundController::class, 'destroy'])->name('refunds.destroy');

	/*
		|--------------------------------------------------------------------------
		| Pricing & Payment Routes
		|--------------------------------------------------------------------------
	*/

	Route::resource('plans', PlanCrudController::class);
	Route::resource('products', ProductCrudController::class);
	Route::resource('discounts', DiscountCrudController::class);
	Route::get('base-prices', [BasePriceCrudController::class, 'index'])->name('base_prices');
	Route::post('update-base-prices', [BasePriceCrudController::class, 'update'])->name('update_base_prices');

	Route::resource('invoices', InvoiceCrudController::class);
	Route::get('deleted/invoices', [InvoiceCrudController::class, 'indexDeleted'])->name('invoices.deleted.list');
	Route::put('invoices/{id}/restore', [InvoiceCrudController::class, 'restore'])->name('invoices.restore');
	Route::resource('subscriptions', SubscriptionCrudController::class);
	Route::post('/subscriptions/start/{id}', [SubscriptionController::class, 'start'])->name('start_subscription');
	Route::post('/subscriptions/renew/{id}', [SubscriptionController::class, 'renew'])->name('renew_subscription');

	/*
		|--------------------------------------------------------------------------
		| Referral Routes
		|--------------------------------------------------------------------------
	*/

	Route::get('referrals', [ReferralController::class, 'index'])->name('referrals.index');
	Route::post('approve-referral/{id}', [ReferralController::class, 'approve'])->name('referrals.approve');
	Route::delete('referrals/{id}', [ReferralController::class, 'destroy'])->name('referrals.destroy');


	/*
		|--------------------------------------------------------------------------
		| Customer Routes
		|--------------------------------------------------------------------------
	*/

	Route::resource('customers', CustomerCrudController::class);
	Route::get('/get-customer-address/{id}', [CustomerCrudController::class, 'address'])->name('get_customer_address');
	Route::post('/update-customer-address/{id}', [CustomerCrudController::class, 'updateAddress'])->name('update_customer_address');

	Route::resource('customer-cars', CustomerCarCrudController::class);


	/*
		|--------------------------------------------------------------------------
		| User Routes
		|--------------------------------------------------------------------------
	*/

	Route::resource('users', UserCrudController::class);
	Route::resource('admins', AdminCrudController::class);
	Route::resource('service-units', ServiceUnitCrudController::class);
	Route::resource('user-groups', UserGroupCrudController::class);

	/*
		|--------------------------------------------------------------------------
		| Search Routes
		|--------------------------------------------------------------------------
	*/

	Route::get('/search-customer-cars', [CustomerCarCrudController::class, 'search'])->name('search_customer_cars');
	Route::get('/search-customers', [CustomerCrudController::class, 'search'])->name('search_customers');
	Route::get('/search-invoices', [InvoiceCrudController::class, 'search'])->name('search_invoices');

	/*
		|--------------------------------------------------------------------------
		| Configuration Routes
		|--------------------------------------------------------------------------
	*/

	Route::resource('banners', BannerCrudController::class);
	Route::resource('cars', CarCrudController::class);
	Route::resource('colors', CarColorCrudController::class);
	Route::resource('brands', CarBrandCrudController::class);

	/*
		|--------------------------------------------------------------------------
		| Settings Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('general-settings', [SettingController::class, 'general'])->name('general_settings');
	Route::get('notification-settings', [SettingController::class, 'notification'])->name('notification_settings');
	Route::get('payment-settings', [SettingController::class, 'payment'])->name('payment_settings');

	Route::get('profile-change-requests', [ChangeRequestController::class, 'profileChangeRequests'])->name('profile_change_requests');
	Route::get('address-change-requests', [ChangeRequestController::class, 'addressChangeRequests'])->name('address_change_requests');

	/*
		|--------------------------------------------------------------------------
		| File Manager Routes
		|--------------------------------------------------------------------------
	*/
	Route::get('/file-manager', [FileController::class, 'index'])->name('file-manager');
	Route::get('file-manager/ckeditor', [FileController::class, 'ckeditor'])->name('file-ckeditor');
	Route::get('file-manager/fm-button', [FileController::class, 'button'])->name('file-button');

	/* export csv */

	Route::get('/export-csv', [ScheduleCrudController::class, 'export']);

	/* Notification */

	Route::resource('notification', NotificationController::class);
	Route::get('/send-notification', [NotificationController::class, 'notify']);

	Route::get('/clear', function () {
		$commands = [
			'storage:link',
			'config:cache',
			'config:clear',
			'cache:clear',
			'route:clear',
			'view:clear',
			'auth:clear-resets',
			'event:clear',
			'queue:clear',
			'queue:flush',
			'schedule:clear-cache'
		];
		$output = [];
		$errors = [];
		foreach ($commands as $command) {
			try {
				Artisan::call($command);
				$output[$command] = Artisan::output();
			} catch (\Exception $e) {
				$errors[$command] = $e->getMessage();
			}
		}
		return $errors ? response()->json(['errors' => $errors], 500) : response()->json(['message' => 'All cache cleared successfully!']);
	});
});

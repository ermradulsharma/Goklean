<?php

namespace App\Http\Controllers\Admin;

use App\Filters\InvoiceFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateInvoiceRequest;
use App\Http\Requests\Admin\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\CustomerCar;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\Subscription;
use App\Notifications\InvoicePaid;
use App\Repositories\BookingRepository;
use App\Repositories\PricingRepository;
use App\Transformers\Admin\CustomerCarTransformer;
use App\Transformers\Admin\CustomerTransformer;
use App\Transformers\Admin\InvoiceSearchTransformer;
use App\Transformers\Admin\InvoiceTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceCrudController extends Controller
{
	/**
	 * List all Invoices
	 *
	 * @param InvoiceFilters $filters
	 * @return \Inertia\Response
	 */
	public function index(InvoiceFilters $filters, Request $request)
	{
		$status = $request->input('status');
		$invoicesQuery = Invoice::with(['customerCar.car', 'customer'])->filter($filters)->orderBy('payment_date', 'desc');
		if (!$status) {
			$invoicesQuery->where('invoices.status', '!=', 'cancelled');
		}
		$invoices = fractal($invoicesQuery->paginate($request->input('perPage', 10)), new InvoiceTransformer())->toArray();
		return Inertia::render('Admin/Invoices', [
			'invoices' => fn() => $invoices,
			'statuses' => [
				['value' => 'created', 'text' => 'Created'],
				['value' => 'pending', 'text' => 'Pending'],
				['value' => 'failed', 'text' => 'Failed'],
				['value' => 'paid', 'text' => 'Paid'],
				['value' => 'cancelled', 'text' => 'Cancelled'],
			]
		]);
	}


	/**
	 * Search invoices
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function search(Request $request)
	{
		$query = $request->get('query');
		return response()->json(['invoices' => fractal(Invoice::where('invoice_id', 'like', '%' . $query . '%')->limit(20)->get(), new InvoiceSearchTransformer())->toArray()['data']]);
	}

	/**
	 * Create a New Invoice
	 *
	 * @param Request $request
	 * @param PricingRepository $pricingRepository
	 * @param BookingRepository $bookingRepository
	 * @return \Inertia\Response
	 */
	public function create(Request $request, PricingRepository $pricingRepository, BookingRepository $bookingRepository)
	{
		$car = CustomerCar::with('car')->findOrFail($request->customer_car_id);
		$customer = Customer::findOrFail($car->customer_id);
		return Inertia::render('Admin/Forms/InvoiceForm', [
			'customer' => fractal($customer, new CustomerTransformer())->toArray()['data'],
			'car' => fractal($car, new CustomerCarTransformer())->toArray()['data'],
			'products' => $pricingRepository->fetchProductsWithPricing($car->car->car_type_id, $customer->user_group_id),
			'preferences' => $bookingRepository->singleWashPreferences(),
			'orderTypes' => [
				['code' => 'single', 'name' => 'Single'],
				['code' => 'bulk', 'name' => 'Bulk'],
			],
			'paymentModes' => [
				['code' => 'online', 'name' => 'Online'],
				['code' => 'offline', 'name' => 'Offline'],
			],
			'statuses' => [
				['code' => 'created', 'name' => 'Created'],
				['code' => 'pending', 'name' => 'Pending'],
				['code' => 'failed', 'name' => 'Failed'],
				['code' => 'paid', 'name' => 'Paid'],
				['code' => 'cancelled', 'name' => 'Cancelled']
			]
		]);
	}

	/**
	 * Store a new invoice
	 *
	 * @param CreateInvoiceRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(CreateInvoiceRequest $request)
	{
		$car = CustomerCar::with('car')->findOrFail($request->customer_car_id);
		$customer = Customer::findOrFail($car->customer_id);
		$wallet = Wallet::select('balance')->where('holder_id', $customer->id)->first();
		$wallet_amount = $wallet->balance;
		$totalPrice = 0;
		$items = [];
		foreach ($request->products as $key => $product) {
			$price = $product['has_discount'] ? $product['discounted_price'] : $product['price'];
			$subTotal = $product['qty'] * $price;
			$totalPrice += $subTotal;
			$items[$product['id']] = [
				'qty' => $product['qty'],
				'price' => $price,
				'sub_total' => $subTotal
			];
		}

		$invoice = new Invoice();
		$invoice->invoice_id = 'GK' . time();
		$invoice->customer_id = $customer->id;
		$invoice->customer_car_id = $car->id;
		$invoice->order_type = $request->order_type;
		$invoice->due_date = $request->due_date;
		$invoice->payment_mode = $request->payment_mode;
		$invoice->payment_date = $request->payment_date;
		$invoice->reference_id = $request->reference_id;
		$invoice->transaction_id = $request->transaction_id;
		$invoice->status = $request->status;
		$invoice->booking_completed = $request->booking_completed;
		$invoice->total_price = $totalPrice;
		$invoice->preferences->set($request->preferences);
		$invoice->data->set([
			'items' => $request->products
		]);
		if ($wallet_amount >= $totalPrice) {
			$invoice->save();
			if($invoice->status == 'paid'){
				$customer->wallet->withdraw(($totalPrice * 100), [
					'description' => 'Invoice payment for Invoice #' . $invoice->id,
				]);
			}
			if ($invoice) {
				$invoice->items()->sync($items);
			}
			return redirect()->route('invoices.index')->with('successMessage', 'Invoice created successfully');
		} else {
			return redirect()->route('invoices.index')->with('errorMessage', 'Wallet amount is less than booking amount');
		}
	}

	/**
	 * Show an Invoice
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$invoice = Invoice::withTrashed()->find($id);
		return response()->json(['invoice' => fractal($invoice, new InvoiceTransformer())->toArray()['data']]);
	}

	/**
	 * Edit an invoice
	 *
	 * @param $id
	 * @return \Inertia\Response
	 */
	public function edit($id)
	{
		$invoice = Invoice::withTrashed()->with(['customer', 'customerCar'])->findOrFail($id);
		$car = CustomerCar::with('car')->findOrFail($invoice->customer_car_id);
		$customer = Customer::findOrFail($invoice->customer_id);
		return Inertia::render('Admin/Forms/InvoiceForm', [
			'invoice' => $invoice,
			'editFlag' => true,
			'customer' => fractal($customer, new CustomerTransformer())->toArray()['data'],
			'car' => fractal($car, new CustomerCarTransformer())->toArray()['data'],
			'orderTypes' => [
				['code' => 'single', 'name' => 'Single'],
				['code' => 'bulk', 'name' => 'Bulk'],
			],
			'paymentModes' => [
				['code' => 'online', 'name' => 'Online'],
				['code' => 'offline', 'name' => 'Offline'],
			],
			'statuses' => [
				['code' => 'created', 'name' => 'Created'],
				['code' => 'pending', 'name' => 'Pending'],
				['code' => 'failed', 'name' => 'Failed'],
				['code' => 'paid', 'name' => 'Paid'],
				['code' => 'cancelled', 'name' => 'Cancelled']
			]
		]);
	}

	/**
	 * Update an invoice
	 *
	 * @param UpdateInvoiceRequest $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateInvoiceRequest $request, $id)
	{
		// dd($request->all());
		$invoice = Invoice::withTrashed()->findOrFail($id);
		if ($invoice->trashed()) {
			$invoice->restore();
		}
		$paidFlag = $invoice->subscription_id && $invoice->status !== 'paid' && $request->status == 'paid' && !$invoice->booking_completed;
		$invoice->update($request->validated());

		// Update billing cycle and renewal info
		if ($paidFlag && !$invoice->booking_completed) {
			$invoice->customer->wallet->withdraw(($invoice->total_price * 100), [
				'description' => 'Invoice payment for Invoice #' . $invoice->id,
			]);
			$paymentDate = Carbon::parse($request->payment_date);
			$billingCycleStarts = Carbon::parse($invoice->billing_cycle_starts);

			$subscription = Subscription::find($invoice->subscription_id);
			$subscription->last_renewed_date = Carbon::parse($request->payment_date)->startOfDay()->toDateTimeString();
			if ($paymentDate->greaterThan($billingCycleStarts)) {
				$subscription->next_renew_date = Carbon::parse($request->payment_date)->addDays(29)->startOfDay()->toDateTimeString();
			} else {
				$subscription->next_renew_date = Carbon::parse($invoice->billing_cycle_ends)->addDay()->startOfDay()->toDateTimeString();
			}
			$subscription->status = 'active';
			$subscription->update();

			if ($paymentDate->greaterThan($billingCycleStarts)) {
				$invoice->billing_cycle_starts = Carbon::parse($request->payment_date)->addDay()->startOfDay()->toDateTimeString();
				$invoice->billing_cycle_ends = Carbon::parse($request->payment_date)->addDays(28)->endOfDay()->toDateTimeString();
				$invoice->update();
			}
		}

		return redirect()->route('invoices.index')->with('successMessage', 'Invoice updated successfully');
	}

	/**
	 * Delete an invoice
	 *
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id)
	{
		try {
			$invoice = Invoice::find($id);
			$subscriptionId = $invoice->subscription_id;

			if ($invoice->subscription_id) {
				$subscription = Subscription::find($invoice->subscription_id);
				if ($invoice->billing_cycle < $subscription->completed_billing_cycles) {
					return redirect()->back()->with('errorMessage', 'Unable to Delete Booking.');
				}
			}

			if (!$invoice->canSecureDelete('bookings')) {
				return redirect()->back()->with('errorMessage', 'Unable to Delete Invoice! Bookings Exist!');
			}

			if ($invoice->status == 'paid') {
				return redirect()->back()->with('errorMessage', 'Unable to delete Paid Invoice! Cancel the invoice try delete!');
			}

			$invoice->items()->detach();

			$invoice->secureDelete('bookings');

			if ($subscriptionId) {
				$subscription = Subscription::find($subscriptionId);
				$subscription->completed_billing_cycles = $subscription->invoices->count();
				$subscription->update();
			}
		} catch (\Illuminate\Database\QueryException $e) {
			return redirect()->back()->with('errorMessage', 'Unable to Delete Invoice . Remove all associations and Try again!');
		}

		return redirect()->back()->with('successMessage', 'Invoice was successfully deleted!');
	}

	public function restore($id)
	{
		$invoice = Invoice::withTrashed()->findOrFail($id);
		$invoice->restore();
		return redirect()->route('invoices.index')->with('successMessage', 'Invoice restored successfully.');
	}

	public function indexDeleted(InvoiceFilters $filters, Request $request)
	{
		return Inertia::render('Admin/DeletedInvoice', [
			'invoices' => function () use ($filters) {
				return fractal(
					Invoice::onlyTrashed()->with(['customerCar' => function ($query) {
						$query->with('car');
					}, 'customer'])->filter($filters)->orderBy('payment_date', 'desc')->paginate(request()->perPage ?? 10),
					new InvoiceTransformer()
				)->toArray();
			},
			'statuses' => [
				['value' => 'created', 'text' => 'Created'],
				['value' => 'pending', 'text' => 'Pending'],
				['value' => 'failed', 'text' => 'Failed'],
				['value' => 'paid', 'text' => 'Paid'],
				['value' => 'cancelled', 'text' => 'Cancelled']
			]
		]);
	}
}

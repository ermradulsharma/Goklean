<?php

namespace App\Http\Controllers\Admin;

use App\Filters\BookingFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookingRequest;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Models\Plan;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\ServiceUnit;
use App\Models\Subscription;
use App\Repositories\BookingRepository;
use App\Transformers\Admin\BookingScheduleTransformer;
use App\Transformers\Admin\BookingTransformer;
use App\Transformers\Admin\InvoiceTransformer;
use App\Transformers\Admin\RevisionTransformer;
use App\Transformers\Admin\ScheduleTransformer;
use App\Transformers\Admin\ServiceUnitTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingCrudController extends Controller
{
	/**
	 * @var BookingRepository
	 */
	private $repository;

	public function __construct(BookingRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * List all bookings
	 *
	 * @param BookingFilters $filters
	 * @return \Inertia\Response
	 */
	public function index(BookingFilters $filters, Request $request)
	{
		$statuses = [];
		foreach (getBookingStatuses() as $key => $value) {
			array_push($statuses, [
				'text' => $value,
				'value' => $key
			]);
		}
		$req = 	$request->all();
		$status = "";

		if (isset($req['status'])) {
			return Inertia::render('Admin/Bookings', [
				'bookings' => function () use ($filters) {
					return fractal(
						Booking::withCount('schedules')->with(['customerCar' => function ($query) {
							$query->with('car');
						}, 'customer', 'owner'])->filter($filters)->orderBy('created_at', 'desc')
							->paginate(request()->perPage != null ? request()->perPage : 10),
						new BookingTransformer()
					)->toArray();
				},
				'statuses' => $statuses,
				'serviceUnits' => fractal(ServiceUnit::active()->get(), new ServiceUnitTransformer())->toArray()['data'],
			]);
		} else {
			return Inertia::render('Admin/Bookings', [
				'bookings' => function () use ($filters) {
					return fractal(
						Booking::withCount('schedules')->with(['customerCar' => function ($query) {
							$query->with('car');
						}, 'customer', 'owner'])->where('bookings.status', '!=', 'cancelled')->filter($filters)->orderBy('created_at', 'desc')
							->paginate(request()->perPage != null ? request()->perPage : 10),
						new BookingTransformer()
					)->toArray();
				},
				'statuses' => $statuses,
				'serviceUnits' => fractal(ServiceUnit::active()->get(), new ServiceUnitTransformer())->toArray()['data'],
			]);
		}
	}

	/**
	 * Show a Subscription
	 *
	 * @param $id
	 * @return \Inertia\Response
	 */
	public function show($id)
	{
		$booking = Booking::withCount('schedules')->with(['customerCar.car', 'schedules.items', 'customer', 'owner'])->find($id);
		if (!$booking) {
			abort(404, 'Booking not found.');
		}

		$statuses = collect(getBookingStatuses())->map(fn($value, $key) => ['text' => $value, 'value' => $key])->values()->all();
		$reasons = collect(getReasons())->map(fn($value, $key) => ['text' => $value, 'value' => $key])->values()->all();
		$serviceUnits = ServiceUnit::active()->get();
		return Inertia::render('Admin/BookingDashboard', [
			'booking' => fractal($booking, new BookingTransformer())->toArray()['data'],
			'schedules' => fractal($booking->schedules, new BookingScheduleTransformer())->toArray()['data'],
			'statuses' => $statuses,
			'reasons' => $reasons,
			'serviceUnits' => fractal($serviceUnits, new ServiceUnitTransformer())->toArray()['data'],
			// 'revisions' => fractal($booking->revisionHistory, new RevisionTransformer())->toArray()['data'],
		]);
	}

	/**
	 * Create a new booking
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
	 */
	public function create(Request $request)
	{
		$invoice = Invoice::withCount('bookings')->with(['customerCar' => function ($query) {
			$query->with('car');
		}, 'customer', 'subscription'])->findOrFail($request->invoice_id);

		$plan = null;

		if ($invoice->booking_completed || $invoice->bookings_count > 0) {
			$invoice->booking_completed = 1;
			$invoice->update();
			return redirect()->back()->with('errorMessage', "Unable to proceed! Booking(s) already exist.");
		}

		if ($invoice->status !== 'paid') {
			return redirect()->back()->with('errorMessage', "Unable to proceed! Invoice {$invoice->invoice_id} is not paid.");
		}

		if ($invoice->subscription) {
			$plan = Plan::find($invoice->subscription->plan_id);
		}

		return Inertia::render('Admin/Forms/BookingForm', [
			'invoice' => fractal($invoice, new InvoiceTransformer())->toArray()['data'],
			'preferences' => $this->repository->formatPreferences($invoice->order_type, $invoice->preferences),
			'serviceUnits' => fractal(ServiceUnit::active()->get(), new ServiceUnitTransformer())->toArray()['data'],
			'defaultServiceUnit' => $invoice->customer->service_unit_id,
			'dates' => $this->repository->getBookingDates([
				'booking_type' => $invoice->order_type,
				'preferences' => $invoice->preferences,
				'billing_cycle_starts' => $invoice->billing_cycle_starts,
				'billing_cycle_ends' => $invoice->billing_cycle_ends,
				'wash_qty_per' => $invoice->subscription ? $plan->wash_qty_per : null,
				'wash_qty' => $invoice->subscription ? $plan->wash_qty : null,
			])
		]);
	}

	/**
	 * Store a new booking and create schedules
	 *
	 * @param StoreBookingRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(StoreBookingRequest $request)
	{
		$invoice = Invoice::withCount('bookings')->with(['customer', 'items'])->findOrFail($request->invoice_id);

		if ($invoice->booking_completed || $invoice->bookings_count > 0) {
			$invoice->booking_completed = 1;
			$invoice->update();
			return redirect()->back()->with('errorMessage', "Unable to proceed! Booking(s) already exist.");
		}

		if ($invoice->status !== 'paid') {
			return redirect()->back()->with('errorMessage', "Unable to proceed! Invoice {$invoice->invoice_id} is not paid.");
		}

		$booking = new Booking();
		$booking->code = 'BK' . time();
		$booking->booking_type = $invoice->order_type;
		$booking->invoice_id = $invoice->id;
		$booking->customer_id = $invoice->customer_id;
		$booking->customer_car_id = $invoice->customer_car_id;
		$booking->service_unit_id = $request->service_unit;
		$booking->created_by = auth()->user()->id;
		$booking->save();

		// Create schedule and items for single wash
		if ($booking->booking_type == 'single') {
			$schedule = new Schedule();
			$schedule->code = 'WS' . time();
			$schedule->booking_id = $booking->id;
			$schedule->date = $request->dates;
			$schedule->customer_id = $invoice->customer_id;
			$schedule->customer_car_id = $invoice->customer_car_id;
			$schedule->service_unit_id = $request->service_unit;
			$schedule->status = 'created';
			$schedule->data->set('address', $invoice->customer->preferences->get('address', []));
			$schedule->save();

			if ($schedule) {
				$items = [];
				foreach ($invoice->items as $item) {
					$items[$item['id']] = [
						'enabled' => 1,
						'status' => 'pending'
					];
				}
				$schedule->items()->sync($items);
			}
		}

		// Create schedule and items for bulk wash
		if ($booking->booking_type == 'bulk') {
			// for items quantity check
			$quantities = [];
			foreach ($invoice->items as $item) {
				$quantities[$item['id']] = 0;
			}
			foreach ($request->dates as $date) {
				$schedule = new Schedule();
				$schedule->code = 'WS' . rand(1111111111, time());
				$schedule->booking_id = $booking->id;
				$schedule->date = $date;
				$schedule->customer_id = $invoice->customer_id;
				$schedule->customer_car_id = $invoice->customer_car_id;
				$schedule->service_unit_id = $request->service_unit;
				$schedule->data->set('address', $invoice->customer->preferences->get('address', []));
				$schedule->status = 'created';
				$schedule->save();

				if ($schedule) {
					$items = [];
					foreach ($invoice->items as $item) {
						$items[$item['id']] = [
							'enabled' => $quantities[$item['id']] < $item['pivot']['qty'],
							'status' => 'pending'
						];
						$quantities[$item['id']]++;
					}
					$schedule->items()->sync($items);
				}
			}
		}

		$invoice->booking_completed = 1;
		$invoice->update();

		return redirect()->route('bookings.index')->with('successMessage', "Bookings created successfully.");
	}

	/**
	 * Edit a booking
	 *
	 * @param $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return Booking::find($id);
	}

	/**
	 * Update Booking
	 *
	 * @param UpdateBookingRequest $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateBookingRequest $request, $id)
	{
		$booking = Booking::with('schedules')->find($id);
		$booking->service_unit_id = $request->service_unit;
		$booking->status = $request->status;
		$booking->update();

		foreach ($booking->schedules as $schedule) {
			$schedule->service_unit_id = $request->service_unit;
			$schedule->update();
		}

		return redirect()->back()->with('successMessage', "Booking updated successfully.");
	}

	/**
	 * Delete a booking
	 *
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id)
	{
		try {
			$booking = Booking::withCount('schedules')->find($id);
			$invoice = Invoice::find($booking->invoice_id);

			if ($booking->schedules_count) {
				return redirect()->back()->with('errorMessage', 'Unable to Delete Booking. Schedules Exist.');
			}

			if (!$booking->canSecureDelete('refunds')) {
				return redirect()->back()->with('errorMessage', 'Unable to Delete Booking! Remove from all associations and try again!');
			}

			if ($invoice->subscription_id) {
				$subscription = Subscription::find($invoice->subscription_id);
				if ($invoice->billing_cycle < $subscription->completed_billing_cycles) {
					return redirect()->back()->with('errorMessage', 'Unable to Delete Booking.');
				}
			}

			foreach ($booking->schedules as $schedule) {
				$schedule->items()->detach();
				$schedule->secureDelete();
			}

			$booking->secureDelete('refunds');

			if ($invoice) {
				$invoice->booking_completed = 0;
				$invoice->update();
			}
		} catch (\Illuminate\Database\QueryException $e) {
			return redirect()->back()->with('errorMessage', 'Unable to Delete Booking . Remove all associations and Try again!');
		}

		return redirect()->back()->with('successMessage', 'Booking was successfully deleted!');
	}
}

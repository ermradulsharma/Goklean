<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ScheduleFilters;
use App\Helpers\FcmHelper;
use App\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Models\User;
use App\Models\ServiceUnit;
use App\Notifications\ScheduleStatus;
use App\Transformers\Admin\ScheduleTransformer;
use App\Transformers\Admin\ServiceUnitTransformer;
use Inertia\Inertia;
use App\Exports\UsersExport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleCrudController extends Controller
{
	/**
	 * List all schedules
	 *
	 * @param ScheduleFilters $filters
	 * @return \Inertia\Response
	 */
	public function index(ScheduleFilters $filters, Request $request)
	{
		$statuses = collect(getBookingStatuses())->map(fn($value, $key) => ['text' => $value, 'value' => $key])->values();
		$reasons = collect(getReasons())->map(fn($value, $key) => ['text' => $value, 'value' => $key])->values();
		$includeCancelled = $request->has('status');
		return Inertia::render('Admin/Schedules', [
			'schedules' => function () use ($filters, $includeCancelled) {
				$query = Schedule::with(['customerCar.car', 'customer', 'booking', 'items', 'serviceUnit']);
				if (!$includeCancelled) {
					$query->where('schedules.status', '!=', 'cancelled');
				}
				return fractal($query->filter($filters)->orderBy('date', 'desc')->paginate(request('perPage', 10)), new ScheduleTransformer())->toArray();
			},
			'statuses' => $statuses,
			'reasons' => $reasons,
			'serviceUnits' => ServiceUnit::select('id as value', 'first_name as text')->active()->get(),
		]);
	}


	/**
	 * Schedule details
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$schedule = Schedule::with(['customerCar' => function ($query) {
			$query->with('car');
		}, 'customer', 'booking', 'items'])->find($id);
		$items = [];
		foreach ($schedule->items as $item) {
			if ($item['pivot']['enabled']) {
				array_push($items, [
					'product_id' => $item['id'],
					'code' => $item['code'],
					'name' => $item['name'],
					'status' => $item['pivot']['status'] ? getBookingStatuses()[$item['pivot']['status']] : '-',
					'reason' => $item['pivot']['reason'] ? getReasons()[$item['pivot']['reason']] : '-'
				]);
			}
		}
		return response()->json([
			'schedule' => fractal($schedule, new ScheduleTransformer())->toArray()['data'],
			'items' => $items
		]);
	}

	/**
	 * Edit a Schedule
	 *
	 * @param $id
	 * @return mixed
	 */
	public function edit($id)
	{
		$schedule = Schedule::with('items')->find($id);
		$items = [];
		foreach ($schedule->items as $item) {
			array_push($items, [
				'product_id' => $item['id'],
				'code' => $item['code'],
				'name' => $item['name'],
				'enabled' => (bool) $item['pivot']['enabled'],
				'status' => $item['pivot']['status'],
				'reason' => $item['pivot']['reason']
			]);
		}
		return response()->json([
			'schedule' => $schedule,
			'items' => $items
		]);
	}

	/**
	 * Update Booking
	 *
	 * @param UpdateScheduleRequest $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateScheduleRequest $request, $id)
	{
		$schedule = Schedule::with('customer')->find($id);
		$schedule->service_unit_id = $request->service_unit;
		$schedule->date = $request->date;
		$schedule->status = $request->status;
		$schedule->update();
		$items = [];
		foreach ($request->items as $item) {
			$items[$item['product_id']] = [
				'enabled' => $item['enabled'],
				'status' => $item['status'],
				'reason' => $item['reason']
			];
		}
		$schedule->items()->sync($items);
		$transformed = fractal($schedule, new ScheduleTransformer())->toArray()['data'];
		if ($schedule->status == 'completed') {
			$sms = new SmsHelper();
			try {
				$sms->sendSMS([
					'flow_id' => $sms->getFlowId('wash_completed'),
					'mobiles' => '91' . $schedule->customer->mobile,
					'date' => $schedule->formatted_date
				]);
			} catch (Exception $e) {
				Log::error($e->getMessage());
			}
		}
		$fcm = new FcmHelper();
		try {
			$fcm->sendPushNotification([
				'registration_ids' => $schedule->customer->preferences->get('fcm_tokens'),
				'notification' => [
					'title' => "Schedule {$transformed['code']} Status",
					'body' => "You car wash scheduled for {$transformed['customer_car']} on {$transformed['date']} has been {$transformed['status']}."
				]
			]);
		} catch (Exception $e) {
			Log::error($e->getMessage());
		}
		try {
			$request->user()->notify(new ScheduleStatus($transformed));
		} catch (Exception $e) {
			Log::error($e->getMessage());
		}

		return redirect()->back()->with('successMessage', "Schedule updated successfully.");
	}

	public function export(ScheduleFilters $filters)
	{

		return Excel::download(new usersExport($filters), 'users.csv');
	}
}

<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CustomerFilters;
use App\Helpers\FcmHelper;
use App\Filters\NotificationFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerAddressRequest;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\User;
use App\Models\City;
use App\Models\Customer;
use App\Models\ServiceUnit;
use App\Models\UserGroup;
use App\Transformers\Admin\CustomerSearchTransformer;
use App\Transformers\Admin\CustomerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Notifications\ScheduleStatus;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
	public function index(NotificationFilters $filters)
	{
		return Inertia::render('Admin/Notifications', [
			'customers' => function () use ($filters) {
				return fractal(
					Customer::with('city', 'wallet')->filter($filters)
						->paginate(request()->perPage != null ? request()->perPage : 10),
					new CustomerTransformer()
				)->toArray();
			},
			'serviceUnits' => ServiceUnit::select('id', 'first_name as name')->get(),
			'userGroups' => UserGroup::select('id', 'name')->get(),
			'cities' => City::select('id', 'name')->get()
		]);
	}

	public function notify()
	{
		/* $curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
				"to": "dYSVt-GsQ1iOu8VZU-RA4Y:APA91bEOiY_sliLiTUTO4bRZZcJfOZSZahnK7Iw1MaPRVYF71693EtmoeOQFFEqp7AMiVCMHKhKZ1UNeJZxjRLTNbFDQYKmCuuSpJ7zOYrSKxWGITbCyDUoHKJzYDmBvnv_tBlkYSCQL",
				"notification": {
				"body": "SELL Event Occur",
				"title": " Sell at "
				},
				"data": {
				"body": "Notification Body",
				"title": "Notification Title",
				"key_1": "Value for key_1",
				"key_2": "Value for key_2"
				}
				}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Key=AAAAEUMG6FU:APA91bE3-i2eUk2cJi5xZAlFQAXZkwo38HzkPlIhzRjrf5GLFk3LVS_wU0IOlocM9LYH6gWNeiZGmkqBUYwRNjDwrrEiF68rGqve42NwDmQzZ4U2YvI_kDt19s_HPNNScezNb9o3MdG1'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		echo $response; */

		$schedule = $user = User::find('18');
		$scheduleData = [
			'code' => 'SCH123456',
			'customer_car' => 'Honda City 2022',
			'date' => now()->format('d M Y'),
			'status' => 'completed'
		];
		// Send SMS Notification
		/* if ($schedule->status == 'completed') {
			$sms = new SmsHelper();
			try {
				$sms->sendSMS([
					'flow_id' => $sms->getFlowId('wash_completed'),
					'mobiles' => '91' . $schedule->customer->mobile,
					'date' => $schedule->formatted_date
				]);
			} catch (\Exception $e) {
				// Do nothing
			}
		} */
		$fcm = new FcmHelper();
		try {
			$fcm->sendPushNotification([
				'registration_ids' => $schedule->customer->preferences->get('fcm_tokens'),
				'notification' => [
					'title' => "Schedule {$scheduleData['code']} Status",
					'body' => "You car wash scheduled for {$scheduleData['customer_car']} on {$scheduleData['date']} has been {$scheduleData['status']}."
				]
			]);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
		}
		try {
			$user->notify(new ScheduleStatus($scheduleData));
		} catch (\Exception $e) {
			Log::error($e->getMessage());
		}

		return redirect()->back()->with('successMessage', "Schedule updated successfully.");
	}
}

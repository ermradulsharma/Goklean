<?php

namespace App\Transformers\Api;

use App\Models\Schedule;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ScheduleTransformer extends TransformerAbstract
{
    public function transform(Schedule $schedule)
    {
        return [
            'id'            => (int) $schedule->id,
            'code'            => (string) $schedule->code,
            'car_name'            => (string) $schedule->customerCar->car->full_name,
            'date_time'            => (string) Carbon::parse($schedule->date)->toDayDateTimeString(),
            'car_color'            => (string) $schedule->customerCar->color,
            'car_image'            => (string) asset($schedule->customerCar->car->image_path),
            'number_plate'            => (string) $schedule->customerCar->number_plate,
            'service_unit'            => (string) $schedule->serviceUnit->name,
            'address'       => (string) implode(', ', [
                $schedule->data->get('address.house_no', ''),
                $schedule->data->get('address.house_name', ''),
                $schedule->data->get('address.address', ''),
                $schedule->data->get('address.area', '')
            ]),
            'booking_status'       => (string) getBookingStatuses()[$schedule->status],
        ];
    }
}

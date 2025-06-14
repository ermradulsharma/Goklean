<?php

namespace App\Transformers\Admin;

use App\Models\Schedule;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ScheduleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Schedule $schedule
     * @return array
     */
    public function transform(Schedule $schedule)
    {
        $items = [];
        foreach ($schedule->items as $item) {
            if ($item['pivot']['enabled']) {
                array_push($items, $item['name']);
            }
        }
        return [
            'id' => $schedule->id,
            'code' => $schedule->code,
            'booking_id' => $schedule->booking->id,
            'booking_code' => $schedule->booking->code,
            'service_unit' => $schedule->serviceUnit->first_name,
            'customer' => $schedule->customer ? $schedule->customer->first_name . ' ' . $schedule->customer->last_name : null,
            'customer_car' => $schedule->customerCar->car->name,
            'date' => Carbon::parse($schedule->date)->toDayDateTimeString(),
            'status' => getBookingStatuses()[$schedule->status],
            'reason' => $schedule->reason ? getReasons()[$schedule->reason] : null,
            'items' => implode(', ', $items),
            'overdue' => ($schedule->status == 'pending' || $schedule->status == 'created') && !Carbon::parse($schedule->date)->isFuture()
        ];
    }
}

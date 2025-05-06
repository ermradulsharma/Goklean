<?php

namespace App\Transformers\Admin;

use App\Models\Schedule;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class BookingScheduleTransformer extends TransformerAbstract
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
                $items[] = $item;
                // array_push($items, $item['name']);
            }
        }
        return [
            'id' => $schedule->id,
            'code' => $schedule->code,
            'date' => Carbon::parse($schedule->date)->toDayDateTimeString(),
            'status' => getBookingStatuses()[$schedule->status],
            'reason' => $schedule->reason ? getReasons()[$schedule->reason] : null,
            'items' => $items, // implode(', ', $items),
            'overdue' => ($schedule->status == 'pending' || $schedule->status == 'created') && !Carbon::parse($schedule->date)->isFuture()
        ];
    }
}

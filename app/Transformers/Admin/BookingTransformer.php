<?php

namespace App\Transformers\Admin;

use App\Models\Booking;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class BookingTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Booking $booking
     * @return array
     */
    public function transform(Booking $booking)
    {
        return [
            'id' => $booking->id,
            'code' => $booking->code,
            'booking_type' => ucwords($booking->booking_type).' ('.$booking->schedules_count.' Washes)',
            'invoice_id' => $booking->invoice->id,
            'invoice_code' => $booking->invoice->invoice_id,
            'customer' => $booking->customer->full_name,
            'customer_car' => $booking->customerCar->car->full_name,
            'created_at' => Carbon::parse($booking->created_at)->toDayDateTimeString(),
            'created_by' => optional($booking->owner)->full_name,
            'status' => getBookingStatuses()[$booking->status],
        ];
    }
}

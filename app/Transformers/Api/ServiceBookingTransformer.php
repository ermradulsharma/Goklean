<?php

namespace App\Http\Transformers;

use App\Models\Booking;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ServiceBookingTransformer extends TransformerAbstract
{
    public function transform(Booking $booking)
    {
        return [
            'id'            => (int) $booking->id,
            'code'            => (string) $booking->booking_code,
            'customer_name'            => (string) $booking->customer->first_name.' '.$booking->customer->last_name,
            'customer_unique_id'            => (string) $booking->customer->unique_code,
            'address'       => (string) $booking->flat_no.", ".$booking->flat_name.", ".$booking->address.", ".$booking->area,
            'car_name'            => (string) strtoupper($booking->car->brand_name." ".$booking->car->name),
            'date_time'            => (string) Carbon::parse($booking->date_time)->toDayDateTimeString(),
            'car_color'            => (string) $booking->customerCar->color,
            'car_image'            => (string) asset($booking->car->image_path),
            'number_plate'            => (string) $booking->customerCar->number_plate,
            'service_unit'            => (string) $booking->serviceUnit->name,
            'has_interior'       => (bool) $booking->is_interior_enabled,
            'booking_status'       => (string) getBookingStatusText($booking->booking_status),
            'interior_status'       => (string) getInteriorStatusText($booking->interior_status),
        ];
    }
}

<?php


namespace App\Repositories;


use Carbon\Carbon;

class BookingRepository
{
    /**
     * Single wash preferences
     *
     * @return string[]
     */
    public function singleWashPreferences()
    {
        return [
            'date_time' => '',
        ];
    }

    /**
     * Bulk wash preferences
     *
     * @return array
     */
    public function bulkWashPreferences()
    {
        return [
            'days' => [
                ['name' => 'Monday', 'selected' => false, 'time' => '00:00:00'],
                ['name' => 'Tuesday', 'selected' => false, 'time' => '00:00:00'],
                ['name' => 'Wednesday', 'selected' => false, 'time' => '00:00:00'],
                ['name' => 'Thursday', 'selected' => false, 'time' => '00:00:00'],
                ['name' => 'Friday', 'selected' => false, 'time' => '00:00:00'],
                ['name' => 'Saturday', 'selected' => false, 'time' => '00:00:00'],
                ['name' => 'Sunday', 'selected' => false, 'time' => '00:00:00']
            ]
        ];
    }

    /**
     * Format Invoice Preferences
     *
     * @param $bookingType
     * @param $preferences
     * @return array|mixed|string
     */
    public function formatPreferences($bookingType, $preferences)
    {
        if($bookingType == 'bulk') {
            $days = [];
            foreach ($preferences['days'] as $day) {
                if($day['selected']) {
                    array_push($days, [
                        'name' => $day['name'],
                        'time' => date("h:i A", strtotime($day['time']))
                    ]);
                }
            }
            return $days;
        } else {
            return $preferences['date_time'] ? Carbon::parse($preferences['date_time'])->toDayDateTimeString() : 'No Preference';
        }
    }

    /**
     * Get booking dates based on preference
     *
     * @param $data
     * @return array|string
     */
    public function getBookingDates($data)
    {
        if($data['booking_type'] == 'bulk') {
            $dates = [];
            foreach ($data['preferences']['days'] as $day) {
                if($day['selected']) {
                    $noOfDays = getNoOfDaysBetween($day['name'], Carbon::parse($data['billing_cycle_starts'])->toDateString(), Carbon::parse($data['billing_cycle_ends'])->toDateString());
                    foreach ($noOfDays as $noOfDay) {
                        array_push($dates, $noOfDay.' '.$day['time']);
                    }
                }
            }
            if($data['wash_qty_per'] == 'monthly') {
                return [
                    $dates[0],
                    $dates[2]
                ];
            } else {
                return $dates;
            }
        } else {
            return $data['preferences']['date_time'] ? Carbon::parse($data['preferences']['date_time'])->toDateTimeString() : '';
        }
    }

}

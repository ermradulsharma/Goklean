<?php

/*
|--------------------------------------------------------------------------
| Generate OTP
|--------------------------------------------------------------------------
|
| generator string which consist of all numeric digits. Iterate for n-times
| and pick a single character from generator and append it to $result
| generate a random number, take modulus of same with length of generator (say i),
| append the character at place (i) from generator to result
|
*/
function generateNumericOTP($n)
{
    $generator = "13579220468";

    $result = "";

    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    return $result;
}

function getBookingStatuses()
{
    return [
        'created' => 'Created',
        'pending' => 'Pending',
        'partially_completed' => 'Partially Completed',
        'completed' => 'Completed',
        'started' => 'Started',
        'in_progress' => 'In Progress',
        'cancelled' => 'Cancelled',
        'rescheduled' => 'Rescheduled',
        'refunded' => 'Refunded'
    ];
}

function getReasons()
{
    return [
        'vehicle_not_available' => 'Vehicle Not Available',
        'maximum_reschedules_exceeded' => 'Maximum Reschedules Exceeded',
        'customer_not_satisfied' => 'Customer Not Satisfied'
    ];
}

/**
 * Get the date range
 *
 * @param $begin
 * @param $end
 * @param null $interval
 * @return array
 * @throws Exception
 */
function dateRange($begin, $end, $interval = null)
{
    $begin = new DateTime($begin);
    $end = new DateTime($end);

    $end = $end->modify('+1 day');
    $interval = new DateInterval($interval ? $interval : 'P1D');

    return iterator_to_array(new DatePeriod($begin, $interval, $end));
}

/**
 * Get no of days between a date range
 *
 * @param $day
 * @param $fromDate
 * @param $toDate
 * @return array
 * @throws Exception
 */
function getNoOfDaysBetween($day, $fromDate, $toDate)
{
    $dates = dateRange($fromDate, $toDate);
    $days = [];

    $filtered_days = array_filter($dates, function ($date) use ($day) {
        return $date->format("l") === $day;
    });

    foreach ($filtered_days as $filtered_day) {
        array_push($days, $filtered_day->format('Y-m-d'));
    }

    return $days;
}

/**
 * Get day from stirng
 *
 * @param $day
 * @return int
 */
function getDayFromString($day) {
    $days = [
        'Monday' => 0,
        'Tuesday' => 1,
        'Wednesday' => 2,
        'Thursday' => 3,
        'Friday' => 4,
        'Saturday' => 5,
        'Sunday' => 6,
    ];

    return $days[$day];
}

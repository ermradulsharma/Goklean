<?php

namespace App\Http\Controllers\Api;

use App\Transformers\Api\ScheduleTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController
{
    public function getCustomerSchedules(Request $request)
    {
        $status = 0;
        switch ($request->status) {
            case 'completed':
                $status = 'completed';
                break;
            case 'pending':
                $status = ['created', 'pending', 'partially_completed', 'rescheduled'];
                break;
            case 'cancelled':
                $status = ['cancelled', 'refunded'];
                break;
            default:
                $status = '';
                break;
        }
        $customer = request()->user();
        if ($request->exists('date')) {
            $date = Carbon::parse($request->date);
            $schedules = $customer->schedules()->whereDate('date', $date->toDateString())->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })->orderBy('date')->get();
        } elseif ($request->exists('today')) {
            $schedules = $customer->schedules()->whereDate('date', Carbon::today())->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })->orderBy('date')->get();
        } elseif ($request->exists('upcoming')) {
            $schedules = $customer->schedules()->whereDate('date', '>', Carbon::today())->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })->orderBy('date')->get();
        } elseif ($request->exists('scheduled')) {
            $schedules = $customer->schedules()->whereDate('date', '>', Carbon::today()->subDays(15))->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })->orderBy('date', 'desc')->get();
        } else {
            $schedules = $customer->schedules()->whereDate('date', '>', Carbon::today()->submonths(3))->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })->orderBy('date', 'desc')->get();
        }
        $data = fractal($schedules, new ScheduleTransformer())->toArray();

        return response()->json($data["data"], 200);
    }
}

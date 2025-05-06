<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\ServiceBookingTransformer;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ServiceUnitController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'device_name' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $su = ServiceUnit::where('username', $request->username)->with('city:id,name')->first();
        if (!$su || !Hash::check($request->password, $su->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 422);
        }
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $su->id,
                'name' => $su->name,
                'username' => $su->username,
                'number_plate' => $su->number_plate,
                'city' => $su->city,
            ],
            'token' => $su->createToken($request->device_name)->plainTextToken
        ], 200);
    }

    public function getBookings(Request $request)
    {
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();
        $bookings = $request->user()->bookings()->whereDate('date_time', '=', $date)->orderBy('date_time', 'asc')->get();
        $data = fractal($bookings, new ServiceBookingTransformer())->toArray();
        return response()->json($data["data"], 200);
    }

    public function updateWash(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => ['required'],
            'status' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $booking = Booking::find($request->booking_id);
        $booking->booking_status = $request->status;
        $booking->update();
        return response()->json(['success' => true], 200);
    }

    public function updateInterior(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => ['required'],
            'status' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $booking = Booking::find($request->booking_id);
        $booking->interior_status = $request->status;
        $booking->update();
        return response()->json(['success' => true], 200);
    }
}

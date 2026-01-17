<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\carServiceImage;
use App\Models\carServiceRecords;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceUnit;
use App\Transformers\Api\ServiceBookingTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ServiceUnitController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'password' => 'required|string|min:8',
            'device_name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($messages) {
                return $messages[0];
            });
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors'  => $errors
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }
        $serviceUnit = ServiceUnit::with('city:id,name')->where('unique_code', $request->username)->first();
        if (!$serviceUnit || !Hash::check($request->password, $serviceUnit->password)) {
            return response()->json([
                'error' => 'The provided credentials are incorrect.',
                'status' => Response::HTTP_UNAUTHORIZED,
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }
        $token = $serviceUnit->createToken($request->device_name)->plainTextToken;
        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'user' => [
                'id' => $serviceUnit->id,
                'name' => $serviceUnit->first_name . ' ' . $serviceUnit->last_name,
                'username' => $serviceUnit->unique_code,
                'number_plate' => $serviceUnit->number_plate,
                'city' => $serviceUnit->city,
            ],
            'token' => $token,
        ], Response::HTTP_OK);
    }

    /* public function getBookings(Request $request)
    {
        try {
            $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();
            $bookings = $request->user()->bookings()->whereDate('date_time', $date)->orderBy('date_time', 'asc')->get();
            $transformed = fractal($bookings, new ServiceBookingTransformer())->toArray();
            return response()->json([
                'success' => true,
                'status' => Response::HTTP_OK,
                'date' => $date->toDateString(),
                'bookings' => $transformed['data']
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to retrieve bookings.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } */
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
            'booking_id' => 'required|integer|exists:bookings,id',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $booking = Booking::find($request->booking_id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
        }
        $booking->interior_status = $request->status;
        $booking->save();
        return response()->json(['success' => true, 'message' => 'Interior status updated successfully.'], 200);
    }

    public function getSchedules(Request $request)
    {
        $userId = $request->user()->id;
        $su = ServiceUnit::find($userId);
        if (!$su) {
            return response()->json(['success' => false, 'message' => 'Service unit not found.'], 404);
        }
        try {
            $date = $request->has('date') ? Carbon::parse($request->date)->startOfDay() : Carbon::today();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid date format.'], 422);
        }
        $bookings = $su->schedules()->with('items')->whereDate('date', $date)->orderBy('date', 'asc')->get();
        $data = fractal($bookings, new ServiceBookingTransformer())->toArray();
        return response()->json($data['data'], 200);
    }

    public function updateSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|integer|exists:schedules,id',
            'status' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $schedule = Schedule::find($request->schedule_id);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.'], 404);
        }
        $schedule->status = $request->status;
        $schedule->save();
        return response()->json(['success' => true, 'message' => 'Schedule status updated successfully.'], 200);
    }

    public function updateItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|integer|exists:schedules,id',
            'product_id' => 'required',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $schedule = Schedule::find($request->schedule_id);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.'], 404);
        }
        if (!$schedule->items()->where('product_id', $request->product_id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Product not associated with this schedule.'], 404);
        }
        $schedule->items()->updateExistingPivot($request->product_id, [
            'status' => $request->status,
        ]);
        return response()->json(['success' => true, 'message' => 'Item status updated successfully.'], 200);
    }

    public function serviceRecords(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'car_service_record_id' => 'nullable|exists:car_service_records,id',
            'notes' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*.wash_type' => 'required|in:before_wash,after_wash',
            'images.*.image_type' => 'nullable|string',
            'images.*.image' => 'required|image|max:5120', // Max 5MB
        ]);

        $serviceUnit = $request->user();

        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($validated['booking_id']);

            if (!empty($validated['car_service_record_id'])) {
                // Update existing record
                $carServiceRecord = CarServiceRecords::findOrFail($validated['car_service_record_id']);
                $carServiceRecord->update([
                    'type' => $request['type'] ?? $carServiceRecord->type,
                    'notes' => $validated['notes'] ?? $carServiceRecord->notes,
                ]);
            } else {
                // Create new record

                $carServiceRecord = CarServiceRecords::create([
                    'service_unit_id' => $serviceUnit->id,
                    'customer_id' => $booking->customer_id,
                    'booking_id' => $booking->id,
                    'type' => $request['type'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);
            }

            // Add images
            foreach ($validated['images'] as $imageData) {
                $carServiceImage = CarServiceImage::create([
                    'car_service_record_id' => $carServiceRecord->id,
                    'wash_type' => $imageData['wash_type'],
                    'image_type' => $imageData['image_type'] ?? null,
                ]);

                if (isset($imageData['image']) && $imageData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $carServiceImage->updateImage($imageData['image'], 'car_service_record');
                } else {
                    Log::warning('Invalid image upload data', $imageData);
                    throw new \Exception("Invalid image file.");
                }
            }

            DB::commit();

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'success' => true,
                'message' => $validated['car_service_record_id'] ? 'Service record updated successfully' : 'Service record created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process service record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => 'Failed to process service record',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function carServiceRecords(Request $request): JsonResponse
    {
        $serviceUnit = $request->user();
        $record = CarServiceRecords::where(['service_unit_id' => $serviceUnit->id])->get();
        if (!$record) {
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Service record not found.'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'data' => $record->load(['images', 'customer', 'booking', 'serviceUnit'])
        ], Response::HTTP_OK);
    }
}

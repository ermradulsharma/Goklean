<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceComplaint;
use App\Models\ServiceComplaintImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ServiceComplaintController extends Controller
{

    public function storeComplaint(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);
        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($messages) {
                return $messages[0];
            });
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors'  => $errors
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $customer = $request->user();
        $booking = Booking::where(['id' => $request['booking_id'], 'customer_id' => $customer->id, 'status' => 'completed'])->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or not eligible for complaint submission.',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        $status = ['resolved', 'closed', 'completed'];
        $existingUnresolvedComplaint = ServiceComplaint::where(['booking_id' => $booking->id, 'customer_id' => $customer->id])->whereNotIn('status', $status)->first();
        if ($existingUnresolvedComplaint) {
            return response()->json([
                'success' => false,
                'message' => $existingUnresolvedComplaint->status == 'created' ? 'You already create a complaint agait this booking' : 'Your complaint is pending. Please wait sometime.',
                'status' => Response::HTTP_CONFLICT 
            ], Response::HTTP_CONFLICT);
        }
        $complaint = ServiceComplaint::create([
            'complaint_no' => 'GKCOM' . time(),
            'customer_id' => $booking->customer_id,
            'booking_id' => $booking->id,
            'service_unit_id' => $booking->service_unit_id,
            'subject' => $request->subject,
            'description' => $request->description,
        ]);
        if (isset($request['images']) && !empty($request['images'])) {
            foreach ($request['images'] as $imageData) {
                $complaintServiceImage = ServiceComplaintImage::create([
                    'service_complaints_id' => $complaint->id,
                ]);
                if (isset($imageData) && $imageData instanceof \Illuminate\Http\UploadedFile) {
                    $complaintServiceImage->updateImage($imageData, 'complaint_service');
                } else {
                    Log::warning('Invalid image upload data', $imageData);
                    throw new \Exception("Invalid image file.");
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Complaint submitted successfully.',
            'status' => Response::HTTP_CREATED,
            'complaint_id' => $complaint->id
        ], Response::HTTP_CREATED);
    }

    public function updateComplaint(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);
        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($messages) {
                return $messages[0];
            });
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors'  => $errors
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $customer = $request->user();
        $complaint = ServiceComplaint::where(['id' => $id, 'customer_id' => $customer->id])->first();
        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found.',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        $booking = Booking::where(['id' => $request['booking_id'], 'customer_id' => $customer->id, 'status' => 'completed'])->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or not eligible for complaint submission.',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        $complaint->update([
            'subject' => $request->subject,
            'description' => $request->description,
        ]);
        $keptImageIds = $request->kept_image_ids;
        $existingImages = ServiceComplaintImage::where('service_complaints_id', $complaint->id)->get();
        foreach ($existingImages as $image) {
            if (!in_array($image->id, $keptImageIds)) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                } else {
                    Log::warning('Attempted to delete non-existent image file: ' . $image->image_path);
                }
                $image->delete();
            }
        }
        if (isset($request['images']) && !empty($request['images'])) {
            foreach ($request['images'] as $imageData) {
                $complaintServiceImage = ServiceComplaintImage::create([
                    'service_complaints_id' => $complaint->id,
                ]);
                if (isset($imageData) && $imageData instanceof \Illuminate\Http\UploadedFile) {
                    $complaintServiceImage->updateImage($imageData, 'complaint_service');
                } else {
                    Log::warning('Invalid image upload data', $imageData);
                    throw new \Exception("Invalid image file.");
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully.',
            'status' => Response::HTTP_OK,
            'complaint_id' => $complaint->id
        ], Response::HTTP_OK);
    }

    public function viewComplaint($id): JsonResponse
    {
        $complaintObj = ServiceComplaint::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Complaint fetch successfully.',
            'status' => Response::HTTP_OK,
            'complaint' => $complaintObj->load('images')
        ], Response::HTTP_OK);
    }

    public function getComplaint(Request $request): JsonResponse
    {
        $customer = $request->user();
        $complaintObj = ServiceComplaint::where(['customer_id' => $customer->id])->get();
        return response()->json([
            'success' => true,
            'message' => 'Complaint fetch successfully.',
            'status' => Response::HTTP_OK,
            'complaint' => $complaintObj->load('images')
        ], Response::HTTP_OK);
    }

    public function getServiceUnitComplaint(Request $request): JsonResponse
    {
        $serviceUnit = $request->user();
        $complaintObj = ServiceComplaint::where(['service_unit_id' => $serviceUnit->id])->get();
        return response()->json([
            'success' => true,
            'message' => 'Complaint fetch successfully.',
            'status' => Response::HTTP_OK,
            'complaint' => $complaintObj->load('images')
        ], Response::HTTP_OK);
    }

    public function viewServiceUnitComplaint(Request $request, $id): JsonResponse
    {
        $serviceUnit = $request->user();
        $complaintObj = ServiceComplaint::where(['service_unit_id' => $serviceUnit->id, 'id' => $id])->first();
        return response()->json([
            'success' => true,
            'message' => 'Complaint fetch successfully.',
            'status' => Response::HTTP_OK,
            'complaint' => $complaintObj->load('images')
        ], Response::HTTP_OK);
    }

    public function updateServiceUnitComplaintStatus(Request $request, $id): JsonResponse
    {
        $serviceUnit = $request->user();
        $complaintObj = ServiceComplaint::where(['service_unit_id' => $serviceUnit->id, 'id' => $id])->first();
        if (!$complaintObj) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found.',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        $complaintObj->update([
            'status' => $request->status,
            'su_reply' => $request->reply,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Complaint update successfully.',
            'status' => Response::HTTP_OK,
            'complaint' => $complaintObj->load('images')
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Filters\RefundFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RefundRequest;
use App\Models\BasePrice;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Refund;
use App\Models\Schedule;
use App\Transformers\Admin\RefundTransformer;
use Carbon\Carbon;
use Inertia\Inertia;

class RefundController extends Controller
{
    /**
     * List all Refunds
     *
     * @param RefundFilters $filters
     * @return \Inertia\Response
     */
    public function index(RefundFilters $filters)
    {
        return Inertia::render('Admin/Refunds', [
            'refunds' => function () use ($filters) {
                return fractal(
                    Refund::with(['customer', 'booking'])->filter($filters)
                        ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new RefundTransformer()
                )->toArray();
            },
            'statuses' => [
                ['value' => 'initiated', 'text' => 'Initiated'],
                ['value' => 'completed', 'text' => 'Completed']
            ]
        ]);
    }

    /**
     * Calculate refund for a booking
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate($id)
    {
        $booking = Booking::withCount(['schedules' => function ($query) {
            $query->where('schedules.status', '=', 'refunded');
        }, 'schedules as total_schedules', 'refund'])->with(['schedules', 'invoice', 'refund', 'customerCar' => function ($query) {
            $query->with('car');
        }])->findOrFail($id);

        if ($booking->refund_count > 0) {
            return response()->json([
                'success' => false,
                'message' => "Refund already {$booking->refund->status}. Refund ID is {$booking->refund->code}."
            ]);
        }

        if ($booking->status !== 'completed' && $booking->status !== 'cancelled' && $booking->status !== 'refunded') {
            return response()->json([
                'success' => false,
                'message' => 'Booking status is not completed/cancelled/refunded.'
            ]);
        }

        if ($booking->schedules_count == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No refundable schedules found for this booking.'
            ]);
        }

        $refundAmount = 0;

        if ($booking->booking_type === 'single') {
            $refundAmount = $booking->invoice->total_price ?? 0;
        } else {
            $refundedItems = [];
            foreach ($booking->schedules as $schedule) {
                foreach ($schedule->items as $item) {
                    if (isset($item->pivot) && $item->pivot->status === 'refunded') {
                        $refundedItems[] = $item;
                    }
                }
            }
            if (empty($refundedItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No refunded items found.'
                ]);
            }
            $codes = array_column($refundedItems, 'code');
            $hasInt = in_array('GK-INT-1W', $codes);
            $hasExt = in_array('GK-EXT-1W', $codes);
            $carTypeId = $booking->customerCar->car->car_type_id ?? null;
            $totalSchedules = $booking->total_schedules;
            $refundedSchedules = $booking->schedules_count;
            $chargeableWashes = $totalSchedules - $refundedSchedules;
            $totalPrice = BasePrice::where('car_type_id', $carTypeId)->where('wash_qty', $totalSchedules)->first();
            $chargeablePrice = BasePrice::where('car_type_id', $carTypeId)->where('wash_qty', $chargeableWashes)->first();
            if ($hasInt && !$hasExt) {
                $refundAmount = 100;
            } elseif ($hasInt && $hasExt) {
                $refundAmount = ($totalPrice->price ?? 0) - ($chargeablePrice->price ?? 0) + 100;
            } else {
                $refundAmount = ($totalPrice->price ?? 0) - ($chargeablePrice->price ?? 0);
            }
        }
        return response()->json([
            'success' => true,
            'refund' => [
                'schedules_count' => $booking->schedules_count,
                'refund_amount' => $refundAmount
            ]
        ]);
    }

    /**
     * Initiate refund request
     *
     * @param RefundRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiate(RefundRequest $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $refund = new Refund();
        $refund->code = 'RF' . time();
        $refund->customer_id = $booking->customer_id;
        $refund->booking_id = $booking->id;
        $refund->refund_type = $request->refund_type;
        $refund->amount = $request->refund_type == 'custom' ? $request->custom_amount : $request->refund_amount;
        $refund->processed_by = auth()->user()->id;
        $refund->refund_date = Carbon::now()->toDateTimeString();
        $refund->data->set('notes', $request->notes);
        $refund->status = 'initiated';
        $refund->save();

        if (!$refund) {
            redirect()->back()->with('errorMessage', 'Unable to finish the request.');
        }

        return redirect()->back()->with('successMessage', 'Refund initiated and pending for approval. Refund ID is ' . $refund->code);
    }

    /**
     * Approve the refund
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $refund = Refund::with('booking')->findOrFail($id);
        $customer = Customer::findOrFail($refund->customer_id);

        if ($refund->status == 'completed') {
            return redirect()->back()->with('successMessage', 'Refund already processed successfully.');
        }

        $transaction = $customer->depositFloat($refund->amount, [
            "title" => "Refund {$refund->code}",
            'description' => "Refund of the booking {$refund->booking->code}."
        ]);

        if ($transaction) {
            $refund->refund_date = Carbon::now()->toDateTimeString();
            $refund->transaction_id = $transaction->id;
            $refund->status = 'completed';
            $refund->update();
        }

        return redirect()->back()->with('successMessage', 'Refund approved successfully. Amount credited to user wallet.');
    }

    /**
     * Delete a Refund
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $refund = Refund::find($id);

            if ($refund->transaction()->count()) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Refund! Transactions exist!');
            }

            $refund->forceDelete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('errorMessage', 'Unable to Delete Refund. Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Refund was successfully deleted!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Subscription;
use App\Transformers\Admin\InvoiceTransformer;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $invoice_dues = Invoice::with(['customerCar' => function ($query) {
            $query->with('car');
        }, 'customer'])
            ->whereIn('status', ['pending', 'created'])
            ->whereMonth('due_date', Carbon::now()->month)
            ->get();

        $booking_dues = Invoice::with(['customerCar' => function ($query) {
            $query->with('car');
        }, 'customer'])->where('status', '=', 'paid')
            ->where('booking_completed', '=', 0)
            ->whereMonth('due_date', Carbon::now()->month)
            ->get();

        // New Statistics
        $total_revenue = Invoice::where('status', 'paid')->sum('total_price');
        $active_subscriptions = Subscription::where('status', 'active')->count();
        $total_customers = Customer::count();
        $monthly_bookings = Booking::whereMonth('created_at', Carbon::now()->month)->count();

        return Inertia::render('Dashboard', [
            'invoice_dues' => fractal($invoice_dues, new InvoiceTransformer())->toArray()['data'],
            'booking_dues' => fractal($booking_dues, new InvoiceTransformer())->toArray()['data'],
            'stats' => [
                'total_revenue' => number_format($total_revenue, 2),
                'active_subscriptions' => $active_subscriptions,
                'total_customers' => $total_customers,
                'monthly_bookings' => $monthly_bookings,
            ]
        ]);
    }
}

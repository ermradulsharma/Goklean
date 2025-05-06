<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Transformers\Admin\InvoiceTransformer;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $invoice_dues = Invoice::with(['customerCar' => function($query) {
            $query->with('car');
        }, 'customer'])
            ->whereIn('status', ['pending', 'created'])
            ->whereMonth('due_date', Carbon::now()->month)
            ->get();

        $booking_dues = Invoice::with(['customerCar' => function($query) {
            $query->with('car');
        }, 'customer'])->where('status', '=', 'paid')
            ->where('booking_completed', '=', 0)
            ->whereMonth('due_date', Carbon::now()->month)
            ->get();

        return Inertia::render('Dashboard', [
            'invoice_dues' => fractal($invoice_dues, new InvoiceTransformer())->toArray()['data'],
            'booking_dues' => fractal($booking_dues, new InvoiceTransformer())->toArray()['data']
        ]);
    }
}

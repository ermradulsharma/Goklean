<?php

namespace App\Http\Controllers\Admin;

use App\Filters\RefundFilters;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Transformers\Admin\ReferralTransformer;
use Inertia\Inertia;

class ReferralController extends Controller
{
    /**
     * List all Refunds
     *
     * @param RefundFilters $filters
     * @return \Inertia\Response
     */
    public function index(RefundFilters $filters)
    {
        return Inertia::render('Admin/Referrals', [
            'referrals' => function () use ($filters) {
                return fractal(
                    Referral::with(['customer', 'referredBy'])->filter($filters)
                        ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new ReferralTransformer()
                )->toArray();
            },
            'statuses' => [
                ['value' => 'pending', 'text' => 'Pending'],
                ['value' => 'accepted', 'text' => 'Accepted'],
                ['value' => 'rejected', 'text' => 'Rejected']
            ]
        ]);
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
            $refund = Referral::find($id);

            if ($refund->transaction()->count()) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Referral! Transactions exist!');
            }

            $refund->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('errorMessage', 'Unable to Delete Referral. Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Referral was successfully deleted!');
    }
}

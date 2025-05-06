<?php

namespace App\Http\Controllers\Admin;

use App\Filters\PlanFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlanRequest;
use App\Http\Requests\Admin\UpdatePlanRequest;
use App\Models\Plan;
use App\Transformers\Admin\PlanTransformer;
use Inertia\Inertia;

class PlanCrudController extends Controller
{
    /**
     * List all Plans
     *
     * @param PlanFilters $filters
     * @return \Inertia\Response
     */
    public function index(PlanFilters $filters)
    {
        return Inertia::render('Admin/Plans', [
            'plans' => function () use($filters) {
                return fractal(Plan::filter($filters)
                    ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new PlanTransformer())->toArray();
            },
        ]);
    }

    /**
     * Store a Plan
     *
     * @param StorePlanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePlanRequest $request)
    {
        Plan::create($request->validated());
        return redirect()->back()->with('successMessage', 'Plan was successfully added!');
    }

    /**
     * Show a Plan
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(Plan::find($id), new PlanTransformer())->toArray();
    }

    /**
     * Edit a Plan
     *
     * @param $id
     * @return Plan|Plan[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return Plan::find($id);
    }

    /**
     * Update a Plan
     *
     * @param UpdatePlanRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePlanRequest $request, $id)
    {
        $plan = Plan::find($id);
        $plan->update($request->validated());
        return redirect()->back()->with('successMessage', 'Plan was successfully updated!');
    }

    /**
     * Delete a Plan
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $plan = Plan::find($id);

            if(!$plan->canSecureDelete('subscriptions')) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Plan! Remove from all associations and try again!');
            }

            $plan->secureDelete('subscriptions');
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('errorMessage', 'Unable to Delete Plan . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Plan was successfully deleted!');
    }
}

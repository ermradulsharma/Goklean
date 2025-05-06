<?php

namespace App\Http\Controllers\Admin;

use App\Filters\DiscountFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiscountRequest;
use App\Http\Requests\Admin\UpdateDiscountRequest;
use App\Models\Discount;
use App\Models\Plan;
use App\Models\Product;
use App\Models\UserGroup;
use App\Transformers\Admin\DiscountTransformer;
use Inertia\Inertia;

class DiscountCrudController extends Controller
{
    /**
     * List all Discounts
     *
     * @param DiscountFilters $filters
     * @return \Inertia\Response
     */
    public function index(DiscountFilters $filters)
    {
        return Inertia::render('Admin/Discounts', [
            'discounts' => function () use($filters) {
                return fractal(Discount::with(['item','userGroup'])->filter($filters)->orderBy('valid_until', 'desc')
                    ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new DiscountTransformer())->toArray();
            },
            'products' => Product::active()->get(['id', 'name']),
            'plans' => Plan::active()->get(['id', 'name']),
            'groups' => UserGroup::get(['id', 'name']),
        ]);
    }

    /**
     * Store a Discount
     *
     * @param StoreDiscountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDiscountRequest $request)
    {
        Discount::create($request->validated());
        return redirect()->back()->with('successMessage', 'Discount was successfully added!');
    }

    /**
     * Show a Discount
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(Discount::find($id), new DiscountTransformer())->toArray();
    }

    /**
     * Edit a Discount
     *
     * @param $id
     * @return Discount|Discount[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function edit($id)
    {
        return Discount::find($id);
    }

    /**
     * Update a Discount
     *
     * @param UpdateDiscountRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDiscountRequest $request, $id)
    {
        $discount = Discount::find($id);
        $discount->update($request->validated());
        return redirect()->back()->with('successMessage', 'Discount was successfully updated!');
    }

    /**
     * Delete a Discount
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $discount = Discount::find($id);

            $discount->secureDelete();
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('errorMessage', 'Unable to Delete Discount . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Discount was successfully deleted!');
    }
}

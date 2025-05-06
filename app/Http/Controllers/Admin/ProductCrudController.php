<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ProductFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\CarType;
use App\Models\Product;
use App\Transformers\Admin\ProductTransformer;
use Inertia\Inertia;

class ProductCrudController extends Controller
{
    /**
     * List all Products
     *
     * @param ProductFilters $filters
     * @return \Inertia\Response
     */
    public function index(ProductFilters $filters)
    {
        return Inertia::render('Admin/Products', [
            'products' => function () use($filters) {
                return fractal(Product::filter($filters)
                    ->paginate(request()->perPage != null ? request()->perPage : 10),
                    new ProductTransformer())->toArray();
            },
            'carTypes' => CarType::orderBy('id', 'desc')->get(['id', 'name'])
        ]);
    }

    /**
     * Store a Product
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());
        return redirect()->back()->with('successMessage', 'Product was successfully added!');
    }

    /**
     * Show a Product
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return fractal(Product::find($id), new ProductTransformer())->toArray();
    }

    /**
     * Edit a Product
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $product = Product::with('prices')->find($id);

        $prices = [];

        foreach($product->prices as $price) {
            $prices[$price->id] = $price->pivot->price;
        }

        return response()->json([
            'product' => $product,
            'prices' => $prices
        ]);
    }

    /**
     * Update a Product
     *
     * @param UpdateProductRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->validated());
        $prices = [];

        foreach($request->prices as $key => $value) {
            if($value != null) {
                $prices[$key] = ['price' => $value];
            }
        }
        $product->prices()->sync($prices);

        return redirect()->back()->with('successMessage', 'Product was successfully updated!');
    }

    /**
     * Delete a Product
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if(!$product->canSecureDelete('invoices')) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Product! Remove from all associations and try again!');
            }

            $product->secureDelete('invoices');
        }
        catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('errorMessage', 'Unable to Delete Product . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Product was successfully deleted!');
    }
}

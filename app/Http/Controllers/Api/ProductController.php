<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Api\PopularProductTransformer;
use App\Http\Transformers\ProductPriceTransformer;
use App\Http\Transformers\ProductTransformer;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function popularProducts()
    {
        $products = Product::with('prices')->where('product_type', '=', 'primary')->where('is_popular', '=', 1)->get();
        $prices = fractal($products, new PopularProductTransformer())->toArray();
        return response()->json($prices['data'], 200);
    }
}

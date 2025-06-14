<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Transformers\Api\PopularProductTransformer;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function popularProducts()
    {
        $products = Product::with('prices')->where(['product_type' => 'primary', 'is_popular' => 1])->get();
        $transformed  = fractal($products, new PopularProductTransformer())->toArray();
        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'message' => 'Popular products fetched successfully.',
            'data' => $transformed['data']
        ], Response::HTTP_OK);
    }
}

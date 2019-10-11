<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use App\Resources\Product\ProductListResource;
use App\Resources\Product\ProductDetailResource;
use App\Queries\IndexQueryBuilder;
use App\Models\Product;

class ProductController extends ApiController
{
    public function index()
    {
        $productList = (new IndexQueryBuilder(Product::class))
            ->setSearchables([
                'name',

            ])
            ->setFilterables(['created_at'])
            ->build()
            ->with('category')
            ->when(request()->has('name'), function ($query) {
                $query->where('name', 'like', request()->input('name'));
            })
            ->when(request()->has('is_recommend'), function ($query) {
                $query->where('is_recommend', request()->input('is_recommend'));
            })
            ->select('products.*')
            ->latest()
            ->paginate($this->pagesize);

        return ProductListResource::collection($productList);
    }

    public function info($productId)
    {
        $product = Product::query()
            ->with('category')
            ->where('id', $productId)
            ->firstOrFail();

        return new ProductDetailResource($product);
    }
}

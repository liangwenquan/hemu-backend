<?php

namespace App\Http\Controllers\Platform;

use App\Models\Product;
use App\Queries\IndexQueryBuilder;
use App\Resources\Product\ProductListResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
    public function index()
    {
        $productList = (new IndexQueryBuilder(Product::class))
            ->setSearchables([
                'category_id',
                'name',

            ])
            ->setFilterables(['created_at'])
            ->build()
            ->with('category')
            ->when(request()->has('category_name'), function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->where('name', request()->input('category_name'));
                });
            })
            ->when(request()->has('category_id'), function ($query) {
                $query->where('category_id', request()->input('category_id'));
            })
            ->when(request()->has('name'), function ($query) {
                $query->where('name', 'like', request()->input('name'));
            })
            ->select('products.*')
            ->latest()
            ->paginate($this->pagesize);

        return ProductListResource::collection($productList);
    }
}

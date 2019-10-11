<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Queries\IndexQueryBuilder;
use App\Resources\Product\ProductDetailResource;
use App\Resources\Product\ProductListResource;
use App\Http\Controllers\Controller;
use App\Models\ModelUtil;
use Mockery\Exception;

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
            ->when(request()->has('enabled'), function ($query) {
                $query->where('enabled', '=', request()->input('enabled'));
            })
            ->select('products.*')
            ->latest()
            ->paginate($this->pagesize);

        return ProductListResource::collection($productList);
    }

    public function create()
    {
        $mProduct = ModelUtil::getInstance(Product::class);

        $params = request()->input();
        $params['cover'] = $params['covers'];

        $product = $mProduct->getModel()->create($params);

        return $this->packOk([
            'id' => $product->id
        ]);
    }

    public function info($productId)
    {
        $product = Product::query()
            ->with('category')
            ->where('id', $productId)
            ->firstOrFail();

        return new ProductDetailResource($product);
    }

    public function update($projectId)
    {
        $mProduct = ModelUtil::getInstance(Product::class);

        $params = request()->input();
        $params['cover'] = $params['covers'];

        $mProduct->edit($projectId, $params);

        return new ProductDetailResource($mProduct->detail($projectId));
    }
}

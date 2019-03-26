<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use App\Queries\IndexQueryBuilder;
use App\Models\Categories;
use App\Resources\Category\CategoriesResources;

class CategoriesController extends ApiController
{
    public function index()
    {
        $categoriesList = (new IndexQueryBuilder(Categories::class))
            ->build()
            ->where('enabled', 1)
            ->get();

        return CategoriesResources::collection($categoriesList);
    }
}

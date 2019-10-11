<?php

namespace App\Http\Controllers\Platform;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Resources\Category\CategoriesResources;
use App\Queries\IndexQueryBuilder;

class CategoryController extends Controller
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

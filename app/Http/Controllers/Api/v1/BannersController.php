<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use App\Queries\IndexQueryBuilder;
use App\Models\Banners;
use App\Resources\Banner\BannerResource;

class BannersController extends ApiController
{
    public function index()
    {
        $bannerList = (new IndexQueryBuilder(Banners::class))
            ->build()
            ->where('enabled', 1)
            ->when(request()->has('type'), function ($query) {
                $query->where('type', request()->input('type'));
            })
            ->get();

        return BannerResource::collection($bannerList);
    }
}

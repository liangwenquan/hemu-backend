<?php

namespace App\Http\Controllers\Api\suining;

use App\Models\Suining\Welfare;
use App\Resources\Suining\Welfare\DetailResource;
use App\Resources\Suining\Welfare\ListResources;
use App\Ship\Http\Json\ApiResource;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Queries\IndexQueryBuilder;

class WelfareController extends ApiController
{
    public function index()
    {
        $list = (new IndexQueryBuilder(Welfare::class))
            ->build()
            ->latest()
            ->paginate($this->pagesize);

        return ListResources::collection($list);
    }

    public function info($welfareId)
    {
        $welfare = Welfare::query()
            ->with("shop")
            ->with("history")
            ->with("history.user")
            ->where("id", $welfareId)
            ->firstOrFail();

        return new DetailResource($welfare);
    }
}

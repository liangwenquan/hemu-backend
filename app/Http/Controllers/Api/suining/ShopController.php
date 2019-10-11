<?php

namespace App\Http\Controllers\Api\suining;

use App\Models\Suining\Shop;
use App\Resources\Suining\Shop\DetailResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function info($shopId)
    {
        $shop = Shop::query()
            ->firstOrFail();

        return new DetailResource($shop);
    }
}

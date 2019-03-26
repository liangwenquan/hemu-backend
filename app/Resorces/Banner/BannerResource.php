<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-21
 * Time: 16:21
 */

namespace App\Resources\Banner;

use App\Ship\Http\Json\ApiResource;


class BannerResource extends ApiResource
{
    public $resources;

    public function toArray($request)
    {
        return [
            'id'       => $this->resource->id,
            'weight'   => $this->resource->weight,
            'img_link' => $this->resource->img_url,
            'enabled'  => $this->resource->enabled,
        ];
    }
}
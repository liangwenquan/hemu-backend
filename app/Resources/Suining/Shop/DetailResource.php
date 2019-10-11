<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-10-08
 * Time: 10:59
 */

namespace App\Resources\Suining\Shop;


use App\Ship\Http\Json\ApiResource;

class DetailResource extends ApiResource
{
    public $resources;

    public function toArray($request)
    {
        return [
            'id'          => $this->resource->id,
            'addr' => $this->resource->addr,
            'average' => $this->resource->average,
            'impression' => $this->resource->content_impression,
            'feature' => $this->resource->content_feature,
            'env' => $this->resource->content_env,
            'business_hours' => $this->resource->business_hours,
            'name' => $this->resource->name,
            'phone' => $this->resource->mobile,
            'map' => sprintf("%s|%s", $this->resource->latitude, $this->resource->longitude),
            'distance' => $this->resource->distance,
        ];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-09-05
 * Time: 20:37
 */

namespace App\Resources\Suining\Welfare;


use App\Ship\Http\Json\ApiResource;

class ListResources extends ApiResource
{
    public $resources;

    public function toArray($request)
    {
        return [
            'id'       => $this->resource->id,
            'title'    => $this->resource->title,
            'start_at' => $this->resource->start_at,
            'end_at'   => $this->resource->end_at,
            'price'    => $this->resource->price ?: 0.00,
            'thumb'    => $this->resource->thumb,
            'total'    => $this->resource->join_total,
            'result'   => 0
        ];
    }
}
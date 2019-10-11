<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-08-14
 * Time: 10:18
 */

namespace App\Resources\Lift\Detail;

use App\Ship\Http\Json\ApiResource;

class DetailResource extends ApiResource
{
    public $resources;

    public function toArray($request)
    {
        return [
            'id'          => $this->resource->id,
            'date'        => $this->resource->date,
            'departure'   => $this->resource->departure,
            'destination' => $this->resource->destination,
            'gender'      => $this->resource->GenderZH,
            'phone'       => $this->resource->phone,
            'depart_at'   => $this->resource->DepartAt,
            'type'        => $this->resource->type,
            'type_des'    => $this->resource->typeZH,
            'vehicle'     => $this->resource->vehicle,
            'name'        => $this->resource->name,
            'price'       => $this->resource->price,
            'remark'      => $this->resource->remark ?: '',
            'surplus'     => sprintf("%s%s", $this->resource->surplus, $this->resource->SurplusSuffix),
            'avatar'      => $this->resource->user->avatar,
            'nickname'    => $this->resource->user->nick_name
        ];
    }
}
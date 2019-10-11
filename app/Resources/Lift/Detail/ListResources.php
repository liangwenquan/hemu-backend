<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-08-12
 * Time: 18:03
 */

namespace App\Resources\Lift\Detail;

use App\Ship\Http\Json\ApiResource;

class ListResources extends ApiResource
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
            'surplus'     => sprintf("%s%s", $this->resource->surplus, $this->resource->SurplusSuffix),
            'avatar'      => $this->resource->user->avatar
        ];
    }
}
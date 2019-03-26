<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-21
 * Time: 17:50
 */

namespace App\Resources\Category;

use App\Ship\Http\Json\ApiResource;

class CategoriesResources extends ApiResource
{
    public $resources;

    public function toArray($request)
    {
        return [
            'id'     => $this->resource->id,
            'name'   => $this->resource->name,
            'weight' => $this->resource->weight,
            'icon'   => $this->resource->icon,
        ];
    }
}
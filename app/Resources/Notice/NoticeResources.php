<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-21
 * Time: 17:04
 */

namespace App\Resources\Notice;

use App\Ship\Http\Json\ApiResource;

class NoticeResources extends ApiResource
{
    public $resources;

    public function toArray($request)
    {
        return [
            'id'       => $this->resource->id,
            'title'   => $this->resource->title,
        ];
    }
}
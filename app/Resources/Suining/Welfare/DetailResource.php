<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-09-20
 * Time: 16:53
 */

namespace App\Resources\Suining\Welfare;


use App\Ship\Http\Json\ApiResource;

class DetailResource extends ApiResource
{
    public $resources;

    public function toArray($request)
    {
        return [
            'id'          => $this->resource->id,
            'name' => $this->resource->title,
            'thumb'    => $this->resource->thumb,
            'description' => $this->resource->description,
            'start_at' => $this->resource->start_at,
            'end_at'   => $this->resource->end_at,
            'content' => $this->resource->content,
            'join' => $this->resource->join_total ?: 0,
            'addr' => $this->resource->addr,
            'shop' => $this->resource->shop,
            'time_now' => time(),
            'avatar' => $this->getAvatar($this->resource->history),
        ];
    }

    protected function getAvatar($joinUser) {
        return collect(collect($joinUser)->pluck('user.avatar'))->map(function ($item){
            return $item = [
                'avatar' => $item
            ];
        });
    }


}
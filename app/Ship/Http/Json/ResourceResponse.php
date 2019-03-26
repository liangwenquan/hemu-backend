<?php

namespace App\Ship\Http\Json;

use Illuminate\Http\Resources\Json\ResourceResponse as BaseResourceResponse;
use Illuminate\Support\Collection;

class ResourceResponse extends BaseResourceResponse
{
    protected function wrap($data, $with = [], $additional = [])
    {
        if ($data instanceof Collection) {
            $data = $data->all();
        }

        if ($this->haveDefaultWrapperAndDataIsUnwrapped($data)) {
            $data = [$this->wrapper() => $data];
        } elseif ($this->haveAdditionalInformationAndDataIsUnwrapped($data, $with, $additional)) {
            $data = [($this->wrapper() ?? 'data') => $data];
        }

        return array_merge_recursive($with, $additional, $data);
    }
}
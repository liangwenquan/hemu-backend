<?php

namespace App\Ship\Http\Json;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BaseResponse;

class LengthAwarePaginatedResourceResponse extends BaseResponse
{
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        return [
            'total' => $paginated['total'] ?? 0,
            'per_page' => $paginated['per_page'] ?? 0,
        ];
    }
}
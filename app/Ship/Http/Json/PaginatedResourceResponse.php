<?php

namespace App\Ship\Http\Json;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BaseResponse;

class PaginatedResourceResponse extends BaseResponse
{
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        return [
            'next_page_url' => $paginated['next_page_url'] ?? null,
            'prev_page_url' => $paginated['prev_page_url'] ?? '',
            'current_page' => $paginated['current_page'] ?? '',
            'per_page' => $paginated['per_page'] ?? '',
        ];
    }
}
<?php

namespace App\Ship\Http\Json;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BaseResponse;

class CursorPaginatedResourceResponse extends BaseResponse
{
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        return [
            'next_cursor' => (string)$paginated['next_cursor'] ?? null,
            'current_cursor' => (string)$paginated['current_cursor'] ?? null,
        ];
    }
}
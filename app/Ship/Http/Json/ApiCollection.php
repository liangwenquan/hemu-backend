<?php

namespace App\Ship\Http\Json;

use App\Ship\Pagination\CursorPaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Str;

class ApiCollection extends ResourceCollection
{
    public function with($request)
    {
        return [
            'code' => 0,
            'msg' => 'ok'
        ];
    }

    public function toResponse($request)
    {
        if (Str::startsWith($request->getHost(), 'admin')
            || Str::startsWith($request->getHost(), 'sma')) {
            return (new LengthAwarePaginatedResourceResponse($this))->toResponse($request);
        }

        if ($this->resource instanceof CursorPaginator) {
            return (new CursorPaginatedResourceResponse($this))->toResponse($request);
        }

        return $this->resource instanceof AbstractPaginator
            ? (new PaginatedResourceResponse($this))->toResponse($request)
            : parent::toResponse($request);
    }
}

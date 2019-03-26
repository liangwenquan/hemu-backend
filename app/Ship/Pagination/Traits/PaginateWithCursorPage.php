<?php

namespace App\Ship\Pagination\Traits;

use App\Ship\Pagination\CursorPaginator;
use Illuminate\Pagination\Paginator;

trait PaginateWithCursorPage
{
    public function scopePaginateWithCursorPage($query)
    {
        $page = Paginator::resolveCurrentPage('cursor');

        $perPage = $this->getPerPage();

        $query->skip(($page - 1) * $perPage)->take($perPage + 1);

        $items = $query->get();

        $hasMore = count($items) > $perPage;

        return new CursorPaginator(
            $items->take($perPage),
            $hasMore ? $page + 1 : ''
        );
    }
}
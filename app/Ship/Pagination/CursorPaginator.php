<?php

namespace App\Ship\Pagination;


use Illuminate\Pagination\AbstractPaginator;

class CursorPaginator extends AbstractPaginator
{
    protected $nextCursor;

    private $currentCursor;

    public function __construct($items, $nextCursor, $currentCursor = null)
    {
        $this->items = $items;
        $this->nextCursor = $nextCursor;
        $this->currentCursor = $currentCursor;
    }

    public static function resolveCurrentPage($pageName = 'cursor', $default = null)
    {
        return request()->input('cursor') ?? $default;
    }

    public function toArray()
    {
        return [
            'data' => $this->items->toArray(),
            'next_cursor' => $this->nextCursor,
            'current_cursor' => $this->currentCursor,
        ];
    }
}
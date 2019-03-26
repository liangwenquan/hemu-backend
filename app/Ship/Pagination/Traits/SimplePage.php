<?php

namespace App\Ship\Pagination\Traits;

use App\Ship\Pagination\SimplePaginator;

trait SimplePage
{
    public function scopeSimplePage($query, $top = false)
    {
        return new SimplePaginator($query, $top);
    }
}
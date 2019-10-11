<?php

namespace App\Support\Tests;

use App\Support\Arr;
use TestCase;

class ArrTest extends TestCase
{
    public function test_array_shuffle()
    {
        $array = [1, 2, 3, 4, 5];
        var_dump(Arr::shuffle_by_seed($array, 1));
        var_dump(Arr::shuffle_by_seed($array, 1));
        var_dump(Arr::shuffle_by_seed($array, 1));
    }
}
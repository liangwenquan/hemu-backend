<?php

namespace App\Support\Tests;

use App\Support\RedisHelper;
use TestCase;

class RedisHelperTest extends TestCase
{
    public function test_redis_helper()
    {
        for ($i = 0; $i < 10000; $i++) {
            RedisHelper::hsetByShard('test:%s', $i, $i);
        }

        for ($i = 0; $i < 10000; $i++) {
            $this->assertEquals(
                $i,
                RedisHelper::hgetByShard('test:%s', $i)
            );
        }
    }
}
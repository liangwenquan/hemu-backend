<?php

namespace App\Ship\Logging\Tests;

use Log;
use TestCase;

class SentryLoggerTest extends TestCase
{
    public function test_logging()
    {
        $now = now();
        Log::info("test $now");
        Log::critical("test $now critical");
    }
}
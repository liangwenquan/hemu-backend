<?php

namespace App\Support\Tests;

use App\Models\Subject;
use App\Support\Time;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use TestCase;

class TimeTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $knownDate = Carbon::create(2017, 10, 8, 14, 45);
        Carbon::setTestNow($knownDate);
    }

    public function test_humanize_macro()
    {
        (new Time)->registerDiffForHumanMacro();

        $this->assertEquals('刚刚', now()->humanize());
        $this->assertEquals('刚刚', now()->subMinutes(59)->humanize());
        $this->assertEquals('1小时前', now()->subHours(1)->humanize());
        $this->assertEquals('1小时前', now()->subHours(1)->subMinutes(5)->humanize());
        $this->assertEquals('3小时前', now()->subHours(3)->subMinutes(5)->humanize());
        $this->assertEquals('5小时前', now()->subHours(6)->addMinute()->humanize());
        $this->assertEquals('上午 08:45', now()->subHours(6)->humanize());
        $this->assertEquals('上午 08:40', now()->subHours(6)->subMinutes(5)->humanize());
        $this->assertEquals('昨天 22:44', Carbon::create(2017, 10, 7, 22, 44)->humanize());
        $this->assertEquals('昨天 14:45', Carbon::create(2017, 10, 7, 14, 45)->humanize());
        $this->assertEquals('前天 22:45', Carbon::create(2017, 10, 6, 22, 45)->humanize());
        $this->assertEquals('2017-10-01 10:00', Carbon::create(2017, 10, 1, 10, 00, 30)->humanize());
    }

    public function test_carbon_diff_for_humans()
    {
        $this->assertEquals(now()->subHour(7)->format('A'), 'abc');

        $this->assertEquals(now()->subHour(1)->diffForHumans(), '1小时前');
        $this->assertEquals(now()->subHour(1)->addMinutes(5)->diffForHumans(), '1小时前');
        $this->assertEquals(now()->subHour(6)->diffForHumans(), '6小时前');

    }

    public function test_eloquent_with_carbon_macro()
    {
        Carbon::macro('test', function () {
            return $this->diffForHumans() . ' test';
        });

        $this->assertTrue(
            Str::endsWith(Subject::first()->created_at->test(), 'test')
        );
    }
}
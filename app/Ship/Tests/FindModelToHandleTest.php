<?php

namespace App\Ship\Tests;

use App\Models\Subject;
use App\Ship\Parents\Job;
use App\Ship\Traits\FindModelToHandle;
use Illuminate\Database\Eloquent\Model;
use Redis;
use TestCase;

class FindModelToHandleTest extends TestCase
{
    const REDIS_KEY = 'fmh';

    public function setUp()
    {
        parent::setUp();
        Redis::del(self::REDIS_KEY);
    }

    protected function tearDown()
    {
        parent::tearDown();
        Redis::del(self::REDIS_KEY);
    }

    public function test_trait()
    {
        $query = Subject::approved()
            ->whereIn('id', range(1, 100));

        $job = new class ($query) extends Job
        {

            use FindModelToHandle;

            public function handleSingle(Model $model)
            {
                Redis::sadd('fmh', $model->getKey());
            }
        };

        $job->handle();

        $this->assertEquals($query->count(), Redis::scard(self::REDIS_KEY));
        $query->get()->each(function (Model $model) {
            $this->assertEquals(
                1,
                Redis::sismember(self::REDIS_KEY, $model->getKey())
            );
        });
    }
}


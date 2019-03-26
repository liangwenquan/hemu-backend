<?php

namespace App\Ship\Tests;

use App\Ship\Tests\Helpers\SubjectWithEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TestCase;

class EloquentEventTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        SubjectWithEvent::disableSearchSyncing();
    }

    public function test_retrieved_event_for_one_model()
    {
        $subject = SubjectWithEvent::query()->first();
        $this->assertContains('modified', $subject->title);
    }

    public function test_retrieved_event_for_multiple_models()
    {
        $subjects = SubjectWithEvent::query()->limit(5)->get();

        $subjects->each(function ($subject) {
            $this->assertContains('modified', $subject->title);
        });
    }
}

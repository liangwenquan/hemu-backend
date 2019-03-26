<?php

namespace App\Ship\Tests;

use App\Models\Subject;
use App\Notifications\Subject\SubjectWasFeatured;
use App\User;
use TestCase;

class NotificationTest extends TestCase
{
    /** @var Subject */
    protected $subject;

    public function setUp()
    {
        parent::setUp();
        $this->subject = Subject::first();
    }

    public function test_cancel_notification()
    {
        /** @var Subject $subject */
        $this->subject = Subject::first();
        $notification = new SubjectWasFeatured($this->subject);
        $this->updateIsFeatured($this->subject, true);

        /** @var User $user */
        $user = User::find(334);
        $user->notify(
            $notification->delay(now()->addSeconds(3))
        );
        $this->updateIsFeatured($this->subject->fresh(), false);
    }

    private function updateIsFeatured(Subject $subject, bool $value)
    {
        Subject::withoutSyncingToSearch(function () use (&$subject, $value) {
            if ($value) {
                $subject->featured_at = now();
            } else {
                $subject->featured_at = null;
            }
            $subject->save();
        });
    }
}

<?php

namespace App\Ship\Notifications;

use Illuminate\Notifications\Events\NotificationSending;
use Log;

class CheckCancellableNotification
{
    const TAG = 'CheckCancellableNotification';

    public function handle(NotificationSending $event)
    {
        if ($event->notification instanceof CancellableNotification) {
            if ($event->notification->dontSend() === true) {
                Log::info(self::TAG . 'dont send');
                return false;
            }
        }
        return true;
    }
}

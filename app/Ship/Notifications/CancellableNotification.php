<?php

namespace App\Ship\Notifications;

interface CancellableNotification
{
    public function dontSend();
}

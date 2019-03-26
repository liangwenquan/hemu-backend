<?php

namespace App\Ship\Monitor;

use App\Ship\Parents\Job;
use App\Ship\Queue\Queues;
use Illuminate\Support\Facades\Redis;
use Log;
use ReflectionClass;
use ReflectionException;

class ReportCongestedQueues extends Job
{
    const TAG = 'ReportCongestedQueues: ';

    public function handle()
    {
        $queues = $this->getQueueNames();
        foreach ($queues as $queueName) {
            $jobCount = Redis::llen("queues:$queueName");
            if ($jobCount > 500) {
                Log::critical(
                    self::TAG . "queue $queueName is congested, job count: $jobCount"
                );
            } else {
                Log::info(self::TAG . "queue $queueName, job count: $jobCount");
            }
        }
    }

    private function getQueueNames()
    {
        try {
            $queues = (new ReflectionClass(
                Queues::class
            ))->getConstants();
            return $queues;
        } catch (ReflectionException $e) {
            return [];
        }
    }
}
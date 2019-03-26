<?php


namespace App\Ship\Http\Cache\ModelIds;


use App\Ship\Parents\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use const Constants\Queues\HIGH;
use Log;

class ClearIdsCache extends Job implements ShouldQueue
{
    const TAG = 'ClearIdsCache ';

    private $key;

    public function __construct($key)
    {
        $this->key = $key;
        $this->queue = HIGH;
    }

    public function handle()
    {
        CachedModelIdsResponse::clearCache($this->key);
        Log::info(self::TAG . "clearing {$this->key}");
    }
}

<?php


namespace App\Ship\Http\Cache\ModelIds;


use const Constants\Redis\PAGINATED_ID_CACHE;
use Illuminate\Support\Facades\Redis;

class CacheModelIds
{
    public function handle(NewCacheableIds $event)
    {
        Redis::hset(
            sprintf(PAGINATED_ID_CACHE, $event->key),
            $event->page,
            $event->ids
        );
    }
}

<?php

namespace App\Ship\Http\Cache\ModelIds;

use App\Http\Controllers\PackApiResponse;
use App\Services\Subject\Presenters\FeaturedBuilderQueuePresenter;
use App\Services\Subject\Presenters\FeaturedPresenter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;
use Log;
use const Constants\Redis\PAGINATED_ID_CACHE;

class CachedModelIdsResponse
{
    const TAG = 'IdsCachedModelsResponse';

    use PackApiResponse;

    private $result;

    private $models;

    private $ids;

    public function __construct($type, $handler, $builder)
    {
        $page = Paginator::resolveCurrentPage();
        if (($cacheKey = self::getCacheKey($type)) &&
            !FeaturedBuilderQueuePresenter::isShowCarrySubject(request()->input('city_id'))) {
            /**
             * 记录当前缓存id，以判断是否能够分页
             */
            $this->ids = Redis::hget($cacheKey, $page);
            if ($this->ids) {
                Log::info(self::TAG . " $type cached, page $page");
                $builder->whereIn('id', explode(',', $this->ids));
                $this->models = $builder
                    ->orderByRaw("FIELD (id, $this->ids)")
                    ->get();
                return;
            }
        }


        Log::info(self::TAG . " $type cache miss, page $page");

        /**
         * @var FeaturedPresenter $handler
         */
        if (method_exists($handler, 'handle')) {
            $this->result = $handler->handle();
        }
    }

    public static function getCacheKey($type)
    {
        return sprintf(PAGINATED_ID_CACHE, $type);
    }

    public static function clearCache($type)
    {
        $key = self::getCacheKey($type);
        return Redis::del($key);
    }

    public function handle()
    {
        if ($this->result) {
            return $this->result;
        }
        return (new Paginator(
            $this->models,
            config('app.paginate'),
            Paginator::resolveCurrentPage()
        ))
            ->hasMorePagesWhen(
                sizeof(explode(',', $this->ids)) >= config('app.paginate')
            );
    }
}

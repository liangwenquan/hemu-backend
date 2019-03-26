<?php

namespace App\Ship\Http\Json;

use Illuminate\Http\Resources\Json\Resource;

class ApiResource extends Resource
{
    // default constructor to make
    // anonymous class work
    public function __construct($resource = null)
    {
        if ($resource) {
            parent::__construct($resource);
        }
    }

    public function with($request)
    {
        return [
            'errcode' => 0,
            'msg' => 'ok'
        ];
    }

    /**
     * 由于 Android 那边解析问题需要自定义 json 对象顺序
     * 等他们改完了可以删除
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function toResponse($request)
    {
        return (new ResourceResponse($this))->toResponse($request);
    }

    public static function collection($resource)
    {
        return new class($resource, get_called_class()) extends ApiCollection
        {

            /**
             * @var string
             */
            public $collects;

            /**
             * Create a new anonymous resource collection.
             *
             * @param  mixed $resource
             * @param  string $collects
             */
            public function __construct($resource, $collects)
            {
                $this->collects = $collects;

                parent::__construct($resource);
            }
        };
    }
}
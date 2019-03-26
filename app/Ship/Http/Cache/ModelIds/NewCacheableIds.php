<?php


namespace App\Ship\Http\Cache\ModelIds;


use App\Events\Event;

class NewCacheableIds extends Event
{
    public $key;
    public $ids;
    public $page;

    public function __construct($key, $page, $models)
    {
        $this->key = $key;
        $this->page = $page;
        $this->ids = collect($models)->map(function ($element) {
            return $element->getKey();
        })->implode(',');
    }
}

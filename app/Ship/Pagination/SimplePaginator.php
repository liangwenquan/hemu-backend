<?php

namespace App\Ship\Pagination;

use Illuminate\Database\Eloquent\Builder;
/**
 * @SuppressWarnings(PHPMD)
 */
class SimplePaginator
{
    private $entity;
    private $current;
    private $total;
    private $last;
    private $api = true;
    private $top;
    public $head = [];

    public function __construct(Builder $entity, bool $top = false)
    {
        $this->entity = $entity;
        $this->total = random_int(50, 100);
        $this->top = $top;
    }

    public function page($perPage = 15, $page = null)
    {
        $this->current = intval(request()->input('page'));
        $this->entity->addSelect([
            "subject.*",
//            "category_subject.subject_category_id as pivot_subject_category_id",
//            "category_subject.subject_id as pivot_subject_id",
        ]);
        if ($this->entity instanceof \Jenssegers\Mongodb\Eloquent\Builder) {
            $total = $this->entity->get()->count();
        } else {
            $total = $this->entity->count();
        }
        $this->last = intval(ceil($total / $perPage));
        if ($this->top && empty(request()->input('subject_alias'))) {
            $this->last += 1;
            if (!empty($page)) {
                $page += 1;
            }
        }
        if ($this->top && empty(request()->input('subject_alias')) && $this->current == 1 && !empty($page)) {
            $this->entity->where('istop', '>', 0)->get();
        } else {
            if (!empty($page)) {
                $this->entity->skip(($page - 1) * $perPage)->take($perPage)->get();
            } else {
                $this->entity->skip(($this->current - 1) * $perPage)->take($perPage)->get();
            }
        }
        if ($this->api) {
            $msg = [
                'code' => 0,
                'msg' => 'ok'
            ];
            $data = [
                "data" => $this->entity->get()->toArray()
            ];
        }
        $content = [
            "total" => $this->total,
            "per_page" => $perPage,
            "current_page" => $this->current,
            "last_page" => $this->last,
            "next_page_url" => $this->current < $this->last ? request()->url() . '?page=' . ($this->current + 1) : null,
            "prev_page_url" => $this->current > 1 ? request()->url() . '?page=' . ($this->current - 1) : null,
            "from" => $perPage * ($this->current - 1) + 1,
            "to" => $this->current < $total ? $perPage * $this->current : $total
        ];
        if (!empty($msg)) {
            return array_merge($msg, $this->head, $content, $data);
        }
        return $content;
    }

    public function setHead(array $arr)
    {
        $this->head = $arr;
        return $this;
    }

    public function paginate($perPage = 15, $page = null)
    {
        return response()->json($this->page($perPage, $page), 200, [], JSON_UNESCAPED_UNICODE);
    }
}

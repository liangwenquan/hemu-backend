<?php

namespace App\Http\Controllers\Api\lift;

use App\Queries\IndexQueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Lift\Detail;
use App\Resources\Lift\Detail\ListResources;
use App\Resources\Lift\Detail\DetailResource;
use App\Models\ModelUtil;

class DetailController extends ApiController
{
    public function index()
    {
        $list = (new IndexQueryBuilder(Detail::class))
            ->build()
            ->with('user')
            ->latest()
            ->paginate($this->pagesize);

        return ListResources::collection($list);
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->pack(401, '用户信息失效，请重新登陆');
        }

        $mLift = ModelUtil::getInstance(Detail::class);

        $params = request()->input();
        $params['time'] = strtotime(sprintf("%s %s:00", $params['date'], $params['time']));
        $params['user_id'] = $user->id;

        $lift = $mLift->create($params);

        return $this->packOk([
            'id' => $lift->id
        ]);
    }

    public function info($id)
    {
        $detail = Detail::query()->findOrFail($id);

        return new DetailResource($detail);
    }
}

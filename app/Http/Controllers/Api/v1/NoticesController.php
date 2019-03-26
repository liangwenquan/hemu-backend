<?php

namespace App\Http\Controllers\Api\v1;

use App\Entities\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Queries\IndexQueryBuilder;
use App\Models\Notice;
use App\Resources\Notice\NoticeResources;

class NoticesController extends ApiController
{
    public function index()
    {
        $noticeList = (new IndexQueryBuilder(Notice::class))
            ->build()
            ->where('enabled', 1)
            ->get();

        return NoticeResources::collection($noticeList);
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends ApiController
{
    public function index()
    {
        $code = request()->header('x-wx-code');
        $iv = request()->header('x-wx-iv');
        $encrypted = request()->header('x-wx-encrypted-data');

        $service = app('wechat.mini_program');

        $sessionInfo = $service->auth->session($code);

        $data = $service->encryptor->decryptData(
            data_get($sessionInfo, 'session_key'),
            $iv,
            $encrypted
        );

        data_set($data, 'skey', $sessionInfo['session_key']);
        data_set($data, 'userinfo', $data);
        info($data);

        return $this->packOk($data);
    }
}

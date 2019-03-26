<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ModelUtil;

class UserController extends ApiController
{
    protected $wechatMini;

    public function __construct()
    {
        $this->wechatMini = app('wechat.mini_program');
    }

    public function register()
    {
        $inputs = request()->input();
        $service = app('wechat.mini_program');

        $sessionInfo = $this->wechatMini->auth->session($inputs['code']);

        $data = $service->encryptor->decryptData(
            data_get($sessionInfo, 'session_key'),
            $inputs['iv'],
            $inputs['encryptedData']
        );

        $mUser = ModelUtil::getInstance(User::class);
        $params = $mUser->getValidInputs([
            'name' => $data['nickName'],
            'nick_name' => $data['nickName'],
            'avatar' => $data['avatarUrl'],
            'sex' => $data['gender'],
            'wx_openid' => $data['openId'],
        ]);

        $user = $mUser->getModel()->create($params);

        $token = auth()->login($user);

        return $this->packOk([
            'uid' => $user['id'],
            'token' => $token
        ]);
    }

    public function login()
    {
        app('log')->debug('test', [
            'host' => '127.0.0.1',
            'user_id' => 556,
            'params' => 'hello1'
        ]);

        die;

        $code = request()->input('code');
        $service = app('wechat.mini_program');

        $sessionInfo = $this->wechatMini->auth->session($code);

        $user = User::findOrFail(['wx_openid' => $sessionInfo['openid']]);

        $token = auth()->login($user);

        return $this->packOk($token);
    }

    public function checkToken()
    {
        dump(111);
        return $this->packOk(1);
    }
}

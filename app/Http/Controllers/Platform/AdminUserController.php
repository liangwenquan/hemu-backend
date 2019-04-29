<?php

namespace App\Http\Controllers\Platform;

use App\Exceptions\Exception;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\ModelUtil;

class AdminUserController extends ApiController
{
    /**
     * AdminUserController constructor.
     */
    public function __construct()
    {
    }

    public function login()
    {
        $credentials = request(['name', 'password']);

        $token = auth('platform')->attempt($credentials);

        return $this->packOk([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('platform')->factory()->getTTL() * 60
        ]);
    }

    public function info()
    {
        $userObj = auth('platform')->user();

        if (!$userObj) {
            return $this->pack(401, '用户信息失效，请重新登陆');
        }

        return $this->packOk($userObj->toArray());
    }

    public function create()
    {
        $this->validate(request(), [
            'name' => 'required',
            'password' => 'required',
        ]);

        $mUser = ModelUtil::getInstance(AdminUser::class);

        $user = $mUser->create([
            'name' => request()->input('name'),
            'password' => bcrypt(request()->input('password'))
        ]);

        return $this->packOk($user->toArray());
    }
}

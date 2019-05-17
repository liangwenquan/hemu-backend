<?php

return [
    'BadRequest' => [
        'code' => 40000,
        'msg' => 'Bad Request'
    ],
    'SmsVerificationFailed' => [
        'code' => 40003,
        'msg' => '短信验证错误',
    ],
    'WrongPassword' => [
        'code' => 40100,
        'msg' => '您输入的密码错误，请重新输入'
    ],
    'AccessDenied' => [
        'code' => 40300,
        'msg' => '这是未授权的内容，无法访问哦'
    ],
    'ActionRequiresJopal' => [
        'code' => 40302,
        'msg' => '只有会员才能访问这个内容哦，快去注册囧伴吧！'
    ],
    'JopalNotApproved' => [
        'code' => 40303,
        'msg' => '您尚未通过审核，请稍候'
    ],
    'EndPointNotFound' => [
        'code' => 40400,
        'msg' => '找不到访问的内容呢'
    ],
    'UserNotFound' => [
        'code' => 40402,
        'msg' => '用户名错误'
    ],
    'UnavailableOrderBasePrice' => [
        'code' => 40411,
        'msg' => '暂时无法在该地区提供服务'
    ],
    'ServerError' => [
        'code' => 50000,
        'msg' => '服务繁忙，请稍候再试'
    ],
];

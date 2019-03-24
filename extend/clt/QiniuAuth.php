<?php

namespace clt;
use think\Config;

// 引入鉴权类
use Qiniu\Auth;

class QiniuAuth
{
    static private $auth;  // 七牛鉴权
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!self::$auth) {
            $accessKey = Config::get('QINIU_ACCESS_KEY');
            $secretKey = Config::get('QINIU_SECRET_KEY');
            self::$auth = new Auth($accessKey, $secretKey);
        }
        return self::$auth;
    }
}
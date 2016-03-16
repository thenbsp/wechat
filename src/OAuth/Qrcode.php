<?php

namespace Thenbsp\Wechat\OAuth;

class Qrcode extends AbstractClient
{
    /**
     * 授权接口地址
     */
    public function resolveAuthorizeUrl()
    {
        return 'https://open.weixin.qq.com/connect/qrconnect';
    }

    /**
     * 授权作用域
     */
    public function resolveScope()
    {
        return $this->scope ?: 'snsapi_login';
    }
}

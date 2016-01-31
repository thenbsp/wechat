<?php

namespace Thenbsp\Wechat\Menu;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Wechat\AccessToken;
use Thenbsp\Wechat\Menu\Exception\MenuException;

class Delete
{
    /**
     * 接口地址
     */
    const DELETE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/delete';

    /**
     * AccessToken 对象
     */
    protected $accessToken;

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 获取响应
     */
    public function doDelete()
    {
        $response = Http::request('GET', static::DELETE_URL)
            ->withAccessToken($this->accessToken)
            ->send();

        if( isset($response['errcode']) && ($response['errcode'] != 0) ) {
            throw new MenuException($response['errmsg'], $response['errcode']);
        }

        return true;
    }
}

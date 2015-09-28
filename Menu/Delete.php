<?php

namespace Thenbsp\Wechat\Menu;

use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Util\Http;

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
        $request = Http::get(self::DELETE_URL, array(
            'query' => array(
                'access_token' => $this->accessToken->getAccessToken()
            )
        ));

        $response = $request->json();

        if( array_key_exists('errcode', $response) &&
            ($response['errcode'] != 0) ) {
            throw new \Exception($response['errcode'].': '.$response['errmsg']);
        }

        return true;
    }
}

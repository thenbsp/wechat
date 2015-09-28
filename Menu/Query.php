<?php

namespace Thenbsp\Wechat\Menu;

use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Util\Http;

class Query
{
    /**
     * 接口地址
     */
    const QUERY_URL = 'https://api.weixin.qq.com/cgi-bin/menu/get';

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
     * 获取响应结果
     */
    public function doQuery()
    {
        $request = Http::get(self::QUERY_URL, array(
            'query' => array(
                'access_token' => $this->accessToken->getAccessToken()
            )
        ));

        $response = $request->json();

        if( array_key_exists('errcode', $response) &&
            ($response['errcode'] != 0) ) {
            throw new \Exception($response['errcode'].': '.$response['errmsg']);
        }

        return $response;
    }
}

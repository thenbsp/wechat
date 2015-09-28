<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Util\Http;

class ServerIp
{
    /**
     * 获取公众号 IP 地址
     */
    const SERVERIP_URL = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';

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
     * 获取服务器 IP 列表
     */
    public function getServerIp()
    {
        $request = Http::get(self::SERVERIP_URL, array(
            'query' => array(
                'access_token' => $this->accessToken->getAccessToken()
            )
        ));

        $response = $request->json();

        if( array_key_exists('ip_list', $response) ) {
            return $response['ip_list'];
        }

        throw new \Exception($response['errcode'].': '.$response['errmsg']);
    }
}

<?php

namespace Thenbsp\Wechat\User;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Wechat\AccessToken;

class User
{
    /**
     * 获取用户信息
     */
    const USERINFO = 'https://api.weixin.qq.com/cgi-bin/user/info';

    /**
     * 批量获取用户
     */
    const BETCH = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget';

    /**
     * Thenbsp\Wechat\Wechat\AccessToken
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
     * 获取用户信息
     */
    public function getByOpenid($openid, $lang = 'zh_CN')
    {
        $query = array(
            'openid'    => $openid,
            'lang'      => $lang
        );

        $response = Http::request('GET', static::USERINFO)
            ->withAccessToken($this->accessToken)
            ->withQuery($query)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return $response;
    }
}

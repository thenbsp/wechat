<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\OAuth\AccessToken;
use Thenbsp\Wechat\OAuth\Exception\OAuthUserException;

class User
{
    /**
     * 网页授权获取用户信息
     */
    const USERINFO = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * Thenbsp\Wechat\OAuth\AccessToken
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
    public function getProfile($lang = 'zh_CN')
    {
        if( !$this->accessToken->isValid() ) {
            $this->accessToken->refresh();
        }

        $query = array(
            'access_token'  => $this->accessToken['access_token'],
            'openid'        => $this->accessToken['openid'],
            'lang'          => $lang
        );

        $response = Http::request('GET', static::USERINFO)
            ->withQuery($query)
            ->send();

        if( array_key_exists('errcode', $response)
            && ($response['errcode'] != 0) ) {
            throw new OAuthUserException($response['errmsg'], $response['errcode']);
        }

        return $response;
    }
}

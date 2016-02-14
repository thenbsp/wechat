<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\OAuth\Exception\AccessTokenException;
use Doctrine\Common\Collections\ArrayCollection;

class AccessToken extends ArrayCollection
{
    /**
     * 刷新 access_token
     */
    const REFRESH = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';

    /**
     * 检测 access_token 是否有效
     */
    const IS_VALID = 'https://api.weixin.qq.com/sns/auth';

    /**
     * 用户 access_token 和公众号是一一对应的
     */
    protected $appid;

    /**
     * 构造方法
     */
    public function __construct($appid, ArrayCollection $options)
    {
        $this->appid = $appid;

        parent::__construct($options->toArray());
    }

    /**
     * 刷新用户 access_token
     */
    public function refresh()
    {
        $query = array(
            'appid'         => $this->appid,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $this['refresh_token']
        );

        $response = Http::request('GET', static::REFRESH)
            ->withQuery($query)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new AccessTokenException($response['errmsg'], $response['errcode']);
        }

        // refresh new access_token from ArrayCollection
        parent::__construct($response->toArray());

        return $this;
    }

    /**
     * 检测用户 access_token 是否有效
     */
    public function isValid()
    {
        $query = array(
            'access_token'  => $this['access_token'],
            'openid'        => $this['openid']
        );

        $response = Http::request('GET', static::IS_VALID)
            ->withQuery($query)
            ->send();

        return ($response['errmsg'] === 'ok');
    }
}

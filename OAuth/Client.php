<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\OAuth\AccessToken;
use Thenbsp\Wechat\OAuth\User;
use Thenbsp\Wechat\Util\Http;

class Client
{
    /**
     * 授权 URL 地址
     */
    const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    /**
     * AccessToken URL
     */
    const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * Wechat 对象
     */
    protected $wechat;

    /**
     * 当前 accessToken 对象
     */
    protected $accessToken;

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }

    /**
     * 获取授权页 URL
     */
    public function getAuthorizeUrl($redirectUri, $scope = 'snsapi_base', $state = null)
    {
        $query = array(
            'appid'         => $this->wechat['appid'],
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => $scope,
            'state'         => $state
        );

        return self::AUTHORIZE_URL.'?'.http_build_query($query);
    }

    /**
     * 授权回调（通过 Code 换取 Token）
     */
    public function getAccessToken($code)
    {
        $request = Http::get(self::ACCESS_TOKEN_URL, array(
            'query' => array(
                'appid'         => $this->wechat['appid'],
                'secret'        => $this->wechat['appsecret'],
                'code'          => $code,
                'grant_type'    => 'authorization_code'
            )
        ));

        $response = $request->json();

        if( array_key_exists('access_token', $response) &&
            array_key_exists('openid', $response) ) {
            return ($this->accessToken = new AccessToken($this->wechat, $response));
        }

        throw new \Exception($response['errcode'].': '.$response['errmsg']);
    }

    /**
     * 获取当前（已授权）用户信息
     */
    public function getUser()
    {
        if( is_null($this->accessToken) ) {
            throw new \Exception('Invalid AccessToken');
        }

        return new User($this->accessToken);
    }
}

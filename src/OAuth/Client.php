<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\OAuth\Exception\OAuthUserException;
use Thenbsp\Wechat\OAuth\Exception\AccessTokenException;

class Client
{
    /**
     * 授权 URL 地址
     */
    const AUTHORIZE = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    /**
     * AccessToken URL
     */
    const ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * scope
     */
    const SNSAPI_BASE       = 'snsapi_base';
    const SNSAPI_USERINFO   = 'snsapi_userinfo';

    /**
     * 公众号 Appid
     */
    protected $appid;

    /**
     * 公众号 AppSecret
     */
    protected $appsecret;

    /**
     * scope
     */
    protected $scope;

    /**
     * state
     */
    protected $state;

    /**
     * redirect url
     */
    protected $redirectUri;

    /**
     * 当前已授权 AccessToken
     */
    protected $accessToken;

    /**
     * 构造方法
     */
    public function __construct($appid, $appsecret)
    {
        $this->appid        = $appid;
        $this->appsecret    = $appsecret;
    }

    /**
     * 设置 scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * 设置 state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * 设置 redirect uri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * 获取授权 URL（不知道为啥，response_type 如果放在 redirect_uri 前面授权页面就显示空白）
     */
    public function getAuthorizeUrl()
    {
        $query = array(
            'appid'         => $this->appid,
            'redirect_uri'  => $this->redirectUri ?: Util::getCurrentUrl(),
            'response_type' => 'code',
            'scope'         => $this->scope ?: static::SNSAPI_BASE,
            'state'         => $this->state
        );

        return static::AUTHORIZE.'?'.http_build_query($query);
    }

    /**
     * 通过 code 换取 AccessToken
     */
    public function getAccessToken($code)
    {
        $query = array(
            'appid'         => $this->appid,
            'secret'        => $this->appsecret,
            'code'          => $code,
            'grant_type'    => 'authorization_code'
        );

        $response = Http::request('GET', static::ACCESS_TOKEN)
            ->withQuery($query)
            ->send();

        if( array_key_exists('errcode', $response)
            && ($response['errcode'] != 0) ) {
            throw new AccessTokenException($response['errmsg'], $response['errcode']);
        }

        return ($this->accessToken = new AccessToken($this->appid, $response));
    }

    /**
     * 获取已授权用户信息
     */
    public function getUserinfo()
    {
        if( !$this->accessToken ) {
            throw new OAuthUserException('Invalid User AccessToken');
        }

        return new Userinfo($this->accessToken);
    }
}

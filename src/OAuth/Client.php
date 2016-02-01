<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\OAuth\Exception\OAuthUserException;
use Thenbsp\Wechat\OAuth\Exception\AccessTokenException;
use Doctrine\Common\Collections\ArrayCollection;

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
     * 网页授权获取用户信息
     */
    const USERINFO = 'https://api.weixin.qq.com/sns/userinfo';

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

        if( isset($response['errcode']) && ($response['errcode'] != 0) ) {
            throw new AccessTokenException($response['errmsg'], $response['errcode']);
        }

        return new AccessToken($this->appid, $response);
    }

    /**
     * 通过网页授权获取的 AccessToken 获取用户信息
     */
    public function getUserinfo(AccessToken $accessToken, $lang = 'zh_CN')
    {
        if( !$accessToken->isValid() ) {
            $accessToken->refresh();
        }

        $query = array(
            'access_token'  => $accessToken['access_token'],
            'openid'        => $accessToken['openid'],
            'lang'          => $lang
        );

        $response = Http::request('GET', static::USERINFO)
            ->withQuery($query)
            ->send();

        if( isset($response['errcode']) && ($response['errcode'] != 0) ) {
            throw new OAuthUserException($response['errmsg'], $response['errcode']);
        }

        return new ArrayCollection($response);
    }
}

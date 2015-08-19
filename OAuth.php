<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Util\Request;
use Thenbsp\Wechat\Exception\OAuthException;

/**
 * 网页授权
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/07/23
 */
class OAuth
{
    /**
     * 获取授权 URL 接口
     */
    const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    /**
     * 获取用户 Token 接口
     */
    const GET_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * 刷新用户 Token 接口
     */
    const REFRESH_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';

    /**
     * 检测用户 AccessToken 是否有效接口
     */
    const ACCESS_TOKEN_IS_VALID = 'https://api.weixin.qq.com/sns/auth';

    /**
     * 获取用户信息接口
     */
    const USERINFO_URL = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * 公众号 APP ID
     */
    protected $appid;

    /**
     * 公众号 APP SECRET
     */
    protected $appsecret;

    /**
     * 构造方法
     */
    function __construct($appid, $appsecret)
    {
        $this->appid        = $appid;
        $this->appsecret    = $appsecret;
    }

    /**
     * 直接跳转到授权页
     */
    public function authorize($redirect_uri, $scope = 'snsapi_base', $state = null)
    {
        header('Location: '.$this->getAuthorizeURL($redirect_uri, $scope, $state));
    }

    /**
     * 获取授权 URL (http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html)
     */
    public function getAuthorizeURL($redirect_uri, $scope = 'snsapi_base', $state = null)
    {
        $params = [];
        $params['appid']            = $this->appid;
        $params['redirect_uri']     = $redirect_uri;
        $params['response_type']    = 'code';
        $params['scope']            = $scope;
        $params['state']            = $state;

        return static::AUTHORIZE_URL.'?'.http_build_query($params);
    }

    /**
     * 授权回调（通过 Code 换取 Token）
     */
    public function getToken($code)
    {
        $params = array(
            'appid'         => $this->appid,
            'secret'        => $this->appsecret,
            'code'          => $code,
            'grant_type'    => 'authorization_code'
        );

        $response = Request::get(static::GET_TOKEN_URL, $params);

        if( isset($response->access_token) &&
            isset($response->openid) ) {

            return $response;
        }

        throw new OAuthException($response->errcode.': '.$response->errmsg);
    }

    /**
     * 刷新 Token
     */
    public function refreshToken($token)
    {
        $params = array(
            'appid'         => $this->appid,
            'refresh_token' => $token->refresh_token,
            'grant_type'    => 'refresh_token'
        );

        $response = Request::get(static::REFRESH_TOKEN_URL, $params);

        if( isset($response->access_token) &&
            isset($response->openid) ) {

            return $response;
        }

        throw new OAuthException($response->errcode.': '.$response->errmsg);
    }

    /**
     * 检测用户 AccessToken 是否有效
     */
    public function accessTokenIsValid($token)
    {
        $params = array(
            'openid'        => $token->openid,
            'access_token'  => $token->access_token
        );

        $response = Request::get(static::ACCESS_TOKEN_IS_VALID, $params);

        return isset($response->errmsg) && ($response->errmsg === 'ok');
    }

    /**
     * 获取当前（已授权）用户信息
     */
    public function getUser($token)
    {
        $params = array(
            'access_token'  => $token->access_token,
            'openid'        => $token->openid
        );

        $response = Request::get(static::USERINFO_URL, $params);

        if( isset($response->openid) &&
            isset($response->nickname) &&
            isset($response->headimgurl) ) {

            return $response;
        }

        throw new OAuthException($response->errcode.': '.$response->errmsg);
    }

}

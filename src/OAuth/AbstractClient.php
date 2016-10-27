<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Http;

abstract class AbstractClient
{
    /**
     * AccessToken URL
     */
    const ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token';

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
     * state manager
     */
    protected $stateManager;

    /**
     * 构造方法
     */
    public function __construct($appid, $appsecret)
    {
        $this->appid        = $appid;
        $this->appsecret    = $appsecret;
        $this->stateManager = new StateManager;
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
     * 获取授权 URL
     */
    public function getAuthorizeUrl()
    {
        if (null === $this->state) {
            $this->state = Util::getRandomString(16);
        }

        $this->stateManager->setState($this->state);

        $query = array(
            'appid'         => $this->appid,
            'redirect_uri'  => $this->redirectUri ?: Util::getCurrentUrl(),
            'response_type' => 'code',
            'scope'         => $this->resolveScope(),
            'state'         => $this->state
        );

        return $this->resolveAuthorizeUrl().'?'.http_build_query($query);
    }

    /**
     * 通过 code 换取 AccessToken
     */
    public function getAccessToken($code, $state = null)
    {
        if (null === $state && !isset($_GET['state'])) {
            throw new \Exception('Invalid Request');
        }

        // http://www.twobotechnologies.com/blog/2014/02/importance-of-state-in-oauth2.html
        $state = $state ?: $_GET['state'];
        if (!$this->stateManager->isValid($state)) {
            throw new \Exception(sprintf('Invalid Authentication State "%s"', $state));
        }

        $query = array(
            'appid'         => $this->appid,
            'secret'        => $this->appsecret,
            'code'          => $code,
            'grant_type'    => 'authorization_code'
        );

        $response = Http::request('GET', static::ACCESS_TOKEN)
            ->withQuery($query)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return new AccessToken($this->appid, $response->toArray());
    }

    /**
     * 授权接口地址
     */
    abstract public function resolveAuthorizeUrl();

    /**
     * 授权作用域
     */
    abstract public function resolveScope();
}

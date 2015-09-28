<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Cache;

class AccessToken
{
    /**
     * AccessToken 接口地址
     */
    const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/token';

    /**
     * 公众号对象
     */
    protected $wechat;

    /**
     * 缓存对象
     */
    protected $cache;

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, Cache $cache)
    {
        $this->wechat   = $wechat;
        $this->cache    = $cache;
    }

    /**
     * 获取 Wechat 对象
     */
    public function getWechat()
    {
        return $this->wechat;
    }

    /**
     * 获取 Cache 对象
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * 获取 AccessToken
     */
    public function getAccessToken()
    {
        $key = $this->_getCacheName();

        if( $value = $this->cache->get($key) ) {
            if( array_key_exists('access_token', $value) &&
                array_key_exists('expires_in', $value) ) {
                return $value['access_token'];
            }
        }

        $value = $this->_getAccessToken();

        // set cache
        $this->cache->set($key, $value, $value['expires_in']);

        return $value['access_token'];
    }

    /**
     * 获取 AccessToken（从 API 获取）
     */
    protected function _getAccessToken()
    {
        $request = Http::get(self::ACCESS_TOKEN_URL, array(
            'query' => array(
                'grant_type'    => 'client_credential',
                'appid'         => $this->wechat['appid'],
                'secret'        => $this->wechat['appsecret']
            )
        ));

        $response = $request->json();

        if( array_key_exists('access_token', $response) &&
            array_key_exists('expires_in', $response) ) {
            return $response;
        }

        throw new \Exception($response['errcode'].': '.$response['errmsg']);
    }

    /**
     * 获取缓存名称
     */
    protected function _getCacheName()
    {
        return $this->wechat['appid'].'_access_token';
    }
}

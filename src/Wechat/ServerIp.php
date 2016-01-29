<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\CacheBridge;
use Thenbsp\Wechat\Bridge\CacheBridgeInterface;
use Thenbsp\Wechat\Wechat\AccessToken;
use Thenbsp\Wechat\Wechat\Exception\ServerIpException;

class ServerIp implements CacheBridgeInterface
{
    /**
     * Cache Bridge
     */
    use CacheBridge;
    
    /**
     * http://mp.weixin.qq.com/wiki/4/41ef0843d6e108cf6b5649480207561c.html
     */
    const SERVER_IP = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';

    /**
     * Thenbsp\Wechat\AccessToken\AccessToken
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
     * 获取微信服务器 IP（默认缓存 1 天）
     */
    public function getIps($cacheLifeTime = 86400)
    {
        if( $data = $this->getFromCache() ) {
            return $data['ip_list'];
        }

        $response = Http::request('GET', static::SERVER_IP)
            ->withAccessToken($this->accessToken)
            ->send();

        if( array_key_exists('errcode', $response) ) {
            throw new ServerIpException($response['errmsg'], $response['errcode']);
        }

        $this->saveToCache($response, $cacheLifeTime);

        return $response['ip_list'];
    }

    /**
     * 获取缓存 ID
     */
    public function getCacheId()
    {
        return str_replace('\\', '_', strtolower(__CLASS__));
    }
}

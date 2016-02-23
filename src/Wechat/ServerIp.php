<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\CacheTrait;
use Thenbsp\Wechat\Wechat\AccessToken;

class ServerIp
{
    /**
     * Cache Trait
     */
    use CacheTrait;
    
    /**
     * http://mp.weixin.qq.com/wiki/4/41ef0843d6e108cf6b5649480207561c.html
     */
    const SERVER_IP = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';

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
     * 获取微信服务器 IP（默认缓存 1 天）
     */
    public function getIps($cacheLifeTime = 86400)
    {
        $cacheId = $this->getCacheId();

        if( $this->cache && $data = $this->cache->fetch($cacheId) ) {
            return $data['ip_list'];
        }

        $response = Http::request('GET', static::SERVER_IP)
            ->withAccessToken($this->accessToken)
            ->send();

        if( $response->containsKey('errcode') ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        if( $this->cache ) {
            $this->cache->save($cacheId, $response, $cacheLifeTime);
        }

        return $response['ip_list'];
    }

    /**
     * 从缓存中清除
     */
    public function clearFromCache()
    {
        return $this->cache
            ? $this->cache->delete($this->getCacheId())
            : false;
    }

    /**
     * 获取缓存 ID
     */
    public function getCacheId()
    {
        return str_replace('\\', '_', strtolower(__CLASS__));
    }
}

<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\CacheTrait;
use Doctrine\Common\Collections\ArrayCollection;

class AccessToken extends ArrayCollection
{
    /**
     * Cache Trait
     */
    use CacheTrait;

    /**
     * http://mp.weixin.qq.com/wiki/14/9f9c82c1af308e3b14ba9b973f99a8ba.html
     */
    const ACCESS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token';

    /**
     * 构造方法
     */
    public function __construct($appid, $appsecret)
    {
        $this->set('appid',     $appid);
        $this->set('appsecret', $appsecret);
    }

    /**
     * 获取 AccessToken（调用缓存，返回 String）
     */
    public function getTokenString()
    {
        $cacheId = $this->getCacheId();

        if( $this->cache && $data = $this->cache->fetch($cacheId) ) {
            return $data['access_token'];
        }

        $response = $this->getTokenResponse();

        if( $this->cache ) {
            $this->cache->save($cacheId, $response, $response['expires_in']);
        }

        return $response['access_token'];
    }

    /**
     * 获取 AccessToken（不缓存，返回原始数据）
     */
    public function getTokenResponse()
    {
        $query = array(
            'grant_type'    => 'client_credential',
            'appid'         => $this['appid'],
            'secret'        => $this['appsecret']
        );

        $response = Http::request('GET', static::ACCESS_TOKEN)
            ->withQuery($query)
            ->send();

        if( $response->containsKey('errcode') ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return $response;
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
        return sprintf('%s_access_token', $this['appid']);
    }
}

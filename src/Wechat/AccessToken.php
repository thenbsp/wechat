<?php

namespace Thenbsp\Wechat\Wechat;

use Doctrine\Common\Collections\ArrayCollection;
use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\CacheBridgeTrait;
use Thenbsp\Wechat\Bridge\CacheBridgeInterface;
use Thenbsp\Wechat\Wechat\Exception\AccessTokenException;

class AccessToken extends ArrayCollection implements CacheBridgeInterface
{
    /**
     * Cache Bridge
     */
    use CacheBridgeTrait;

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
     * 获取 AccessToken（调用缓存）
     */
    public function getTokenString()
    {
        $cacheId = $this->getCacheId();

        if( $this->cacheDriver && $data = $this->cacheDriver->fetch($cacheId) ) {
            return $data['access_token'];
        }

        $response = $this->getTokenResponse();

        if( array_key_exists('errcode', $response) ) {
            throw new AccessTokenException($response['errmsg'], $response['errcode']);
        }

        if( $this->cacheDriver ) {
            $this->cacheDriver->save($cacheId, $response, $response['expires_in']);
        }

        return $response['access_token'];
    }

    /**
     * 获取 AccessToken（直接调用接口）
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

        return $response;
    }

    /**
     * 获取缓存 ID
     */
    public function getCacheId()
    {
        return sprintf('%s_access_token', $this['appid']);
    }
}

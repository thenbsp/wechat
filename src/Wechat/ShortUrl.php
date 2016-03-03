<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\CacheTrait;
use Thenbsp\Wechat\Wechat\AccessToken;

class ShortUrl
{
    /**
     * Cache Trait
     */
    use CacheTrait;

    /**
     * http://mp.weixin.qq.com/wiki/6/856aaeb492026466277ea39233dc23ee.html
     */
    const SHORT_URL = 'https://api.weixin.qq.com/cgi-bin/shorturl';

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
     * 获取短链接
     */
    public function toShort($longUrl, $cacheLifeTime = 86400)
    {
        $cacheId = md5($longUrl);

        if( $this->cache && $data = $this->cache->fetch($cacheId) ) {
            return $data;
        }

        $body = array(
            'action'    => 'long2short',
            'long_url'  =>  $longUrl
        );

        $response = Http::request('POST', static::SHORT_URL)
            ->withAccessToken($this->accessToken)
            ->withBody($body)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        if( $this->cache ) {
            $this->cache->save($cacheId, $response['short_url'], $cacheLifeTime);
        }

        return $response['short_url'];
    }
}

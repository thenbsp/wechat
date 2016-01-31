<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Wechat\AccessToken;
use Thenbsp\Wechat\Wechat\Exception\ShortUrlException;

class ShortUrl
{
    /**
     * http://mp.weixin.qq.com/wiki/6/856aaeb492026466277ea39233dc23ee.html
     */
    const SHORT_URL = 'https://api.weixin.qq.com/cgi-bin/shorturl';

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
     * 获取短链接
     */
    public function getShortUrl($longUrl)
    {
        $options = array(
            'action'    => 'long2short',
            'long_url'  => (string) $longUrl
        );

        $response = Http::request('POST', static::SHORT_URL)
            ->withAccessToken($this->accessToken)
            ->withBody($options)
            ->send();

        if( isset($response['errcode']) && ($response['errcode'] != 0) ) {
            throw new ShortUrlException($response['errmsg'], $response['errcode']);
        }

        return $response['short_url'];
    }
}

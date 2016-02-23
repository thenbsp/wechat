<?php

namespace Thenbsp\Wechat\Payment\Address;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\OAuth\AccessToken;

class ConfigGenerator
{
    /**
     * Thenbsp\Wechat\OAuth\AccessToken
     */
    protected $accessToken;

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->setAccessToken($accessToken);
    }

    /**
     * 设置用户 AccessToken
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        if( !$accessToken->isValid() ) {
            $accessToken->refresh();
        }

        $this->accessToken = $accessToken;
    }

    /**
     * 获取配置
     */
    public function getConfig($asArray = false)
    {
        $options = array(
            'appid'         => $this->accessToken->getAppid(),
            'url'           => Util::getCurrentUrl(),
            'timestamp'     => Util::getTimestamp(),
            'noncestr'      => Util::getRandomString(),
            'accesstoken'   => $this->accessToken['access_token']
        );

        // 按 ASCII 码排序
        ksort($options);

        $signature = http_build_query($options);
        $signature = urldecode($signature);
        $signature = sha1($signature);

        $config = array(
            'appId'     => $options['appid'],
            'scope'     => 'jsapi_address',
            'signType'  => 'sha1',
            'addrSign'  => $signature,
            'timeStamp' => $options['timestamp'],
            'nonceStr'  => $options['noncestr'],
        );

        return $asArray ? $config : Serializer::jsonEncode($config);
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }
}

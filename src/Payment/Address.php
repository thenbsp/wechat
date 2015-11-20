<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\OAuth\AccessToken;

class Address
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
     * 获取共享收货地址配置
     */
    public function getConfig($asArray = false)
    {
        $protocol = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443))
            ? 'https://' : 'http://';

        $currentUrl = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        $wechat = $this->accessToken->getWechat();
        $params = array(
            'appid'         => $wechat['appid'],
            'url'           => $currentUrl,
            'timestamp'     => (string) time(),
            'noncestr'      => uniqid(),
            'accesstoken'   => $this->accessToken['access_token']
        );

        // 按 ASCII 码排序
        ksort($params);

        $addrSign = http_build_query($params);
        $addrSign = urldecode($addrSign);
        $addrSign = sha1($addrSign);

        $config = array(
            'appId'     => $params['appid'],
            'scope'     => 'jsapi_address',
            'signType'  => 'sha1',
            'addrSign'  => $addrSign,
            'timeStamp' => $params['timestamp'],
            'nonceStr'  => $params['noncestr'],
        );

        return $asArray ? $config : Serialize::encode($config, 'json');
    }
}

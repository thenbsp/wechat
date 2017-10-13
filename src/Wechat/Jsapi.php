<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\CacheTrait;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Wechat\Jsapi\Ticket;

class Jsapi
{
    /*
     * Cache Trait
     */
    use CacheTrait;

    /**
     * Thenbsp\Wechat\Wechat\AccessToken.
     */
    protected $accessToken;

    /**
     * 是否开起调试.
     */
    protected $debug = false;

    /**
     * 接口列表.
     */
    protected $api = [];

    /**
     * 当前 URL 地址（签名参数）.
     */
    protected $currentUrl;

    /**
     * 全部接口.
     */
    protected $apiValids = [
        'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo',
        'onMenuShareQZone', 'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice',
        'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice', 'chooseImage',
        'previewImage', 'uploadImage', 'downloadImage', 'translateVoice', 'getNetworkType',
        'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems',
        'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closeWindow',
        'scanQRCode', 'chooseWXPay', 'openProductSpecificView', 'addCard', 'chooseCard', 'openCard',
    ];

    /**
     * 构造方法.
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 注入签名地址
     */
    public function setCurrentUrl($url)
    {
        $this->currentUrl = $url;

        return $this;
    }

    /**
     * 注入接口.
     */
    public function addApi($apis)
    {
        if (is_array($apis)) {
            foreach ($apis as $api) {
                $this->addApi($api);
            }
        }

        $apiName = (string) $apis;

        if (!in_array($apiName, $this->apiValids, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid Api: %s', $apiName));
        }

        array_push($this->api, $apiName);

        return $this;
    }

    /**
     * 启用调试模式.
     */
    public function enableDebug()
    {
        $this->debug = true;

        return $this;
    }

    /**
     * 获取配置文件.
     */
    public function getConfig($asArray = false)
    {
        $ticket = new Ticket($this->accessToken);

        if ($this->cache) {
            $ticket->setCache($this->cache);
        }

        $options = [
            'jsapi_ticket' => $ticket->getTicketString(),
            'timestamp' => Util::getTimestamp(),
            'url' => $this->currentUrl ?: Util::getCurrentUrl(),
            'noncestr' => Util::getRandomString(),
        ];

        ksort($options);

        $signature = sha1(urldecode(http_build_query($options)));
        $configure = [
            'appId' => $this->accessToken['appid'],
            'nonceStr' => $options['noncestr'],
            'timestamp' => $options['timestamp'],
            'signature' => $signature,
            'jsApiList' => $this->api,
            'debug' => (bool) $this->debug,
        ];

        return $asArray ? $configure : Serializer::jsonEncode($configure);
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }
}

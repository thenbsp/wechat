<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\CacheTrait;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Wechat\Jsapi\Ticket;
use Thenbsp\Wechat\Wechat\AccessToken;

class Jsapi
{
    /**
     * Cache Trait
     */
    use CacheTrait;

    /**
     * Thenbsp\Wechat\Wechat\AccessToken
     */
    protected $accessToken;

    /**
     * Thenbsp\Wechat\Wechat\Jsapi\Ticket
     */
    protected $ticket;

    /**
     * 是否开起调试
     */
    protected $debug = false;

    /**
     * 接口列表
     */
    protected $api = array();

    /**
     * 全部接口
     */
    protected $apiValids = array(
        'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo',
        'onMenuShareQZone', 'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice',
        'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice', 'chooseImage',
        'previewImage', 'uploadImage', 'downloadImage', 'translateVoice', 'getNetworkType',
        'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems',
        'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closeWindow',
        'scanQRCode', 'chooseWXPay', 'openProductSpecificView', 'addCard', 'chooseCard', 'openCard'
    );

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $ticket = new Ticket($accessToken);

        if( $this->cache ) {
            $ticket->setCache($this->cache);
        }

        $this->ticket       = $ticket;
        $this->accessToken  = $accessToken;
    }

    /**
     * 注入接口
     */
    public function addApi($apis)
    {
        if( is_array($apis) ) {
            foreach( $apis AS $api ) {
                $this->addApi($api);
            }
        }

        $apiName = (string) $apis;

        if( !in_array($apiName, $this->apiValids, true) ) {
            throw new \InvalidArgumentException(sprintf('Invalid Api: %s', $apiName));
        }

        array_push($this->api, $apiName);

        return $this;
    }

    /**
     * 启用调试模式
     */
    public function enableDebug()
    {
        $this->debug = true;

        return $this;
    }

    /**
     * 获取配置文件
     */
    public function getConfig($asArray = false)
    {
        $options = array(
            'jsapi_ticket'  => $this->ticket->getTicketString(),
            'timestamp'     => Util::getTimestamp(),
            'url'           => Util::getCurrentUrl(),
            'noncestr'      => uniqid()
        );

        // 按 ASCII 码排序
        ksort($options);

        $signature = sha1(urldecode(http_build_query($options)));
        $configure = array(
            'appId'     => $this->accessToken['appid'],
            'nonceStr'  => $options['noncestr'],
            'timestamp' => $options['timestamp'],
            'signature' => $signature,
            'jsApiList' => $this->api,
            'debug'     => (bool) $this->debug
        );

        return $asArray ? $configure : Serializer::jsonEncode($configure);
    }

    /**
     * Jsapi Ticket 对象
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }
}

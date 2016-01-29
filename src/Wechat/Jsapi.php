<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Wechat\Jsapi\Ticket;

class Jsapi
{
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
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
        } else {
            if( !in_array($apis, $this->apiValids, true) ) {
                throw new \InvalidArgumentException(sprintf('Invalid Api: %s', $apis));
            }
            array_push($this->api, $apis);
        }

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
        $accessToken    = $this->ticket->getAccessToken();
        $ticket         = $this->ticket->getTicketString();

        $options = array(
            'jsapi_ticket'  => $ticket,
            'timestamp'     => Util::getTimestamp(),
            'url'           => Util::getCurrentUrl(),
            'noncestr'      => uniqid()
        );

        // 按 ASCII 码排序
        ksort($options);

        $signature = sha1(urldecode(http_build_query($options)));
        $configize = array(
            'appId'     => $accessToken['appid'],
            'nonceStr'  => $options['noncestr'],
            'timestamp' => $options['timestamp'],
            'signature' => $signature,
            'jsApiList' => $this->api,
            'debug'     => (bool) $this->debug
        );

        return $asArray ? $configize : (new Serializer)->jsonEncode($configize);
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }
}
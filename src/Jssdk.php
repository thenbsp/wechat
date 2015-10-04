<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Ticket;
use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Util\Serialize;

class Jssdk
{
    /**
     * AccessToken 对象
     */
    protected $accessToken;

    /**
     * Ticket 对象
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
        $this->accessToken  = $accessToken;
        $this->ticket       = new Ticket($accessToken);
    }

    /**
     * 注入接口
     */
    public function addApi($apis)
    {
        if( is_string($apis) ) {
            if( !in_array($apis, $this->apiValids, true) ) {
                throw new \InvalidArgumentException(sprintf('Invalid Api: %s', $apis));
            }
            array_push($this->api, $apis);
        } else {
            foreach( (array) $apis AS $api ) {
                $this->addApi($api);
            }
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
        $wechat = $this->accessToken->getWechat();
        $ticket = $this->ticket->getTicket();

        $protocol = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443))
            ? 'https://' : 'http://';

        $currentUrl = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        $params = array(
            'jsapi_ticket'  => $ticket,
            'timestamp'     => (string) time(),
            'noncestr'      => uniqid(),
            'url'           => $currentUrl
        );

        // 按 ASCII 码排序
        ksort($params);

        $signature = http_build_query($params);
        $signature = urldecode($signature);
        $signature = sha1($signature);

        $config = array(
            'appId'     => $wechat['appid'],
            'nonceStr'  => $params['noncestr'],
            'timestamp' => $params['timestamp'],
            'signature' => $signature,
            'jsApiList' => $this->api
        );

        if( $this->debug ) {
            $config['debug'] = true;
        }

        return $asArray ? $config : Serialize::encode($config, 'json');
    }
}

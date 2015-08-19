<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\JSON;
use Thenbsp\Wechat\Util\SignGenerator;
use Thenbsp\Wechat\Exception\TicketException;

/**
 * 生成 JSSDK 配置
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/07/21
 */
class Jssdk
{
    /**
     * jssdk config
     */
    protected $config = array();

    /**
     * 构造方法
     */
    public function __construct($appid, $appsecret)
    {
        $o = new Wechat($appid, $appsecret);

        try {
            $ticket = $o->getTicket('jsapi');
        } catch (TicketException $e) {
            exit($e->getMessage());
        }

        $signGenerator = new SignGenerator;
        $signGenerator->setUpper(false);
        $signGenerator->setHashType('sha1');
        $signGenerator->addParams('jsapi_ticket', $ticket);
        $signGenerator->addParams('timestamp', time());
        $signGenerator->addParams('noncestr', Util::randomString());
        $signGenerator->addParams('url', Util::currentUrl());

        $this->config = array(
            'appId'     => $appid,
            'nonceStr'  => $signGenerator->getParams('noncestr'),
            'timestamp' => $signGenerator->getParams('timestamp'),
            'signature' => $signGenerator->getResult()
        );
    }

    /**
     * 获取配置文件（json）
     */
    public function getConfig(array $api, $debug = false, $asArray = false)
    {
        $this->config['jsApiList'] = $api;

        if( $debug ) {
            $this->config['debug'] = true;
        }

        return $asArray ? $this->config : JSON::encode($this->config);
    }

}
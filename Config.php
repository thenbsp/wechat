<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\JSON;
use Thenbsp\Wechat\Util\SignGenerator;
use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Exception\PaymentException;

/**
 * 用于生成微信各种 JS 配置文件
 */
class Config
{
    public static function getJssdk(Wechat $wechat, $apis, $debug = false, $asArray = false)
    {
        try {
            $ticket = $wechat->getTicket('jsapi');
        } catch (TicketException $e) {
            exit($e->getMessage());
        }

        $bag = new Bag();
        $bag->set('jsapi_ticket',   $ticket);
        $bag->set('timestamp',      time());
        $bag->set('noncestr',       Util::randomString());
        $bag->set('url',            Util::currentUrl());

        $signGenerator = new SignGenerator($bag);
        $signGenerator->setUpper(false);
        $signGenerator->setHashType('sha1');

        $config = array(
            'appId'     => $wechat->getAppid(),
            'nonceStr'  => $bag->get('noncestr'),
            'timestamp' => $bag->get('timestamp'),
            'signature' => $signGenerator->getResult(),
            'jsApiList' => $apis
        );

        if( $debug ) {
            $config['debug'] = true;
        }

        return $asArray ? $config : JSON::encode($config);
    }

    /**
     * 获取二维码支付地址（模式 1）
     */
    public static function getForeverPayurl(Bag $bag, $key)
    {
        if( !$bag->has('time_stamp') ) {
            $bag->set('time_stamp', time());
        }

        if( !$bag->has('nonce_str') ) {
            $bag->set('nonce_str', Util::randomString());
        }

        $requireds = array('appid', 'mch_id', 'time_stamp', 'nonce_str', 'product_id');

        foreach($requireds AS $property) {
            if( !$bag->has($property) ) {
                exit(sprintf('"%s" is required', $property));
            }
        }

        foreach($bag AS $property => $value) {
            if( !in_array($property, $requireds) ) {
                exit(sprintf('Invalid argument "%s"', $property));
            }
        }

        $signGenerator = new SignGenerator($bag);
        $signGenerator->onSortAfter(function($bag) use ($key) {
            $bag->set('key', $key);
        });

        $bag->set('sign', $signGenerator->getResult());
        $bag->remove('key');

        $query = http_build_query($bag->all());
        
        return 'weixin://wxpay/bizpayurl?'.urlencode($query);
    }

    /**
     * 获取二维码支付地址（模式 2）
     */
    public static function getTemporaryPayurl(Unifiedorder $unifiedorder)
    {
        try {
            $response = $unifiedorder->getResponse();
        } catch (PaymentException $e) {
            exit($e->getMessage());
        }

        if( !isset($response['code_url']) ) {
            exit('Invalid Unifiedorder');
        }

        return urlencode($response['code_url']);
    }

    /**
     * 微信支付 WeixinJSBridge invoke 方式配置文件
     */
    public static function getPaymentConfig(Unifiedorder $unifiedorder, $asArray = false)
    {
        $bag = $unifiedorder->getBag();
        $res = $unifiedorder->getResponse();

        $config = new Bag();
        $config->set('appId',      $bag->get('appid'));
        $config->set('timeStamp',  time());
        $config->set('nonceStr',   $res['nonce_str']);
        $config->set('package',    'prepay_id='.$res['prepay_id']);
        $config->set('signType',   'MD5');

        $signGenerator = new SignGenerator($config);
        $signGenerator->onSortAfter(function($that) use ($unifiedorder) {
            $that->set('key', $unifiedorder->getKey());
        });

        $config->set('paySign', $signGenerator->getResult());
        $config->remove('key');

        return $asArray ? $config->all() : JSON::encode($config->all());
    }

    /**
     * 微信支付 JSSDK chooseWXPay 方式配置文件
     */
    public static function getPaymentJssdkConfig(Unifiedorder $unifiedorder, $asArray = false)
    {
        $config = static::getPaymentConfig($unifiedorder, true);
       
        $bag = new Bag();
        $bag->set('timestamp', $config['timeStamp']);
        $bag->set('nonceStr', $config['nonceStr']);
        $bag->set('package', $config['package']);
        $bag->set('signType', $config['signType']);
        $bag->set('paySign', $config['paySign']);


        return $asArray ? $bag->all() : JSON::encode($bag->all());
    }
}
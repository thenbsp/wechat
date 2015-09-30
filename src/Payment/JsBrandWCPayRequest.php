<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Util\OptionAccess;
use Thenbsp\Wechat\Payment\Unifiedorder;

class JsBrandWCPayRequest extends OptionAccess
{
    /**
     * JsBrandWCPayRequest 方式的 trade_type 为 JSAPI
     */
    const TRADE_TYPE = 'JSAPI';

    /**
     * Wechat 对象
     */
    protected $wechat;

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $optionsForUnifiedorder)
    {
        // 将 trade_type 强制设为 JSAPI
        $optionsForUnifiedorder['trade_type'] = self::TRADE_TYPE;

        // 去统一下单接口下单
        $unifiedorder = new Unifiedorder($wechat, $optionsForUnifiedorder);

        // 下单结果中的 prepay_id
        $response = $unifiedorder->getResponse();

        $this->wechat = $wechat;

        parent::__construct(array(
            'package' => sprintf('prepay_id=%s', $response['prepay_id'])
        ));
    }

    /**
     * 获取配置文件
     */
    public function getConfig($asArray = false)
    {
        $options = $this->getOptions();
        $options['paySign'] = $this->generatePaySign();

        return $asArray ? $options : Serialize::encode($options, 'json');
    }

    /**
     * 生成 paySign
     */
    protected function generatePaySign()
    {
        $options = $this->getOptions();

        ksort($options);

        $signature = http_build_query($options);
        $signature = urldecode($signature);
        $signature = strtoupper(md5($signature.'&key='.$this->wechat['mchkey']));

        return $signature;
    }

    /**
     * 配置选项
     */
    protected function configureOptions($resolver)
    {
        $defined = array('appId', 'timeStamp', 'nonceStr', 'package', 'signType');

        $defaults = array(
            'appId'     => $this->wechat['appid'],
            'timeStamp' => time(),
            'nonceStr'  => uniqid(),
            'signType'  => 'MD5'
        );

        $resolver
            ->setDefined($defined)
            ->setRequired($defined)
            ->setDefaults($defaults);
    }
}
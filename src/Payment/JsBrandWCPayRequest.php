<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Payment\Unifiedorder;

class JsBrandWCPayRequest extends Unifiedorder
{
    /**
     * JsBrandWCPayRequest 方式的 trade_type 为 JSAPI
     */
    const TRADE_TYPE = 'JSAPI';

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $optionsForUnifiedorder)
    {
        // 将 trade_type 强制设为 JSAPI
        $optionsForUnifiedorder['trade_type'] = self::TRADE_TYPE;

        parent::__construct($wechat, $optionsForUnifiedorder);
    }

    /**
     * 获取配置文件
     */
    public function getConfig($asArray = false)
    {
        // 下单结果中的 prepay_id
        $response = $this->getResponse();

        $options = array(
            'appId'     => $this->wechat['appid'],
            /**
             * 微支支付接口 BUG 说明：
             * timeStamp 必需为 String 型，否则在 iOS 下会报 “调用支付jsapi缺少参数: $key0$ 错误” 错误
             */
            'timeStamp' => (string) time(),
            'nonceStr'  => uniqid(),
            'package'   => 'prepay_id='.$response['prepay_id'],
            'signType'  => 'MD5'
        );

        ksort($options);

        $signature = http_build_query($options);
        $signature = urldecode($signature);
        $signature = strtoupper(md5($signature.'&key='.$this->wechat['mchkey']));

        $options['paySign'] = $signature;

        return $asArray ? $options : Serialize::encode($options, 'json');
    }
}

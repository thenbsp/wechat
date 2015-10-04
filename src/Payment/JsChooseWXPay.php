<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Payment\JsBrandWCPayRequest;

class JsChooseWXPay
{
    /**
     * JsChooseWXPay 方式的 trade_type 为 JSAPI
     */
    const TRADE_TYPE = 'JSAPI';

    /**
     * BrandWCPayRequest 对象
     */
    protected $brandWCPayRequest;

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $optionsForUnifiedorder)
    {
        $this->brandWCPayRequest = new JsBrandWCPayRequest($wechat, $optionsForUnifiedorder);
    }

    /**
     * 获取配置文件
     */
    public function getConfig($asArray = false)
    {
        $configs = $this->brandWCPayRequest->getConfig(true);
        $options = array(
            'timestamp' => $configs['timeStamp'],
            'nonceStr'  => $configs['nonceStr'],
            'package'   => $configs['package'],
            'signType'  => $configs['signType'],
            'paySign'   => $configs['paySign']
        );

        return $asArray ? $options : Serialize::encode($options, 'json');
    }
}

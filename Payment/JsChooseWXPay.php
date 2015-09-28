<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Payment\JsBrandWCPayRequest;

class JsChooseWXPay extends JsBrandWCPayRequest
{
    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $optionsOfUnifiedorder)
    {
        parent::__construct($wechat, $optionsOfUnifiedorder);
    }

    /**
     * 获取配置文件
     */
    public function getConfig($asArray = false)
    {
        $options = array(
            'timestamp' => $this->options['timeStamp'],
            'nonceStr'  => $this->options['nonceStr'],
            'package'   => $this->options['package'],
            'signType'  => $this->options['signType'],
            'paySign'   => $this->generatePaySign()
        );

        return $asArray ? $options : Serialize::encode($options, 'json');
    }
}

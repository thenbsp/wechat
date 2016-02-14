<?php

namespace Thenbsp\Wechat\Payment\Jsapi;

class PayChoose extends ConfigGenerator
{
    /**
     * 分解配置
     */
    public function resolveConfig()
    {
        return array(
            'timestamp' => $this['timeStamp'],
            'nonceStr'  => $this['nonceStr'],
            'package'   => $this['package'],
            'signType'  => $this['signType'],
            'paySign'   => $this['paySign']
        );
    }
}

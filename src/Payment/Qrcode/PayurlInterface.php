<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

interface PayurlInterface
{
    /**
     * 获取支付链接
     */
    public function getPayurl();
}
<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Payment\Unifiedorder;

/**
 * 微信扫码支付之临时模式（模式二）
 */
class QrcodeTemporary extends Unifiedorder
{
    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $optionsOfUnifiedorder)
    {
        parent::__construct($wechat, $optionsOfUnifiedorder);
    }

    /**
     * 获取支付 URL
     */
    public function getPayurl()
    {
        $response = $this->getResponse();

        if( !array_key_exists('code_url', $response) ) {
            throw new \Exception('Invalid Unifiedorder Response');
        }

        return urlencode($response['code_url']);
    }
}
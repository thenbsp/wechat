<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Payment\Unifiedorder;

class Temporary
{
    /**
     * Thenbsp\Wechat\Payment\Unifiedorder
     */
    protected $unifiedorder;

    /**
     * 构造方法
     */
    public function __construct(Unifiedorder $unifiedorder)
    {
        $unifiedorder->set('trade_type', 'NATIVE');

        $this->unifiedorder = $unifiedorder;
    }

    /**
     * 获取支付链接
     */
    public function getPayurl()
    {
        $response = $this->unifiedorder->getResponse();

        if( !$response->containsKey('code_url') ) {
            throw new \Exception('Invalid Unifiedorder Response');
        }

        return $response['code_url'];
    }
}

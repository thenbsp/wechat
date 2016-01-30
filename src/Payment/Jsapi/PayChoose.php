<?php

namespace Thenbsp\Wechat\Payment\Jsapi;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Payment\Unifiedorder;

class PayChoose
{
    protected $unifiedorder;

    public function __construct(Unifiedorder $unifiedorder)
    {
        $this->unifiedorder = $unifiedorder;
    }

    public function getConfig($asArray = false)
    {
        $response   = $this->unifiedorder->getResponse();
        $key        = $this->unifiedorder->getKey();

        $options = array(
            'timestamp' => Util::getTimestamp(),
            'nonceStr'  => Util::getRandomString(),
            'package'   => 'prepay_id='.$response['prepay_id'],
            'signType'  => 'MD5'
        );

        // 按 ASCII 码排序
        ksort($options);

        $signature = urldecode(http_build_query($options));
        $signature = strtoupper(md5($signature.'&key='.$key));

        $options['paySign'] = $signature;

        return $asArray ? $options : (new Serializer)->jsonEncode($options);
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }
}

<?php

namespace Thenbsp\Wechat\Payment\Jsapi;

use Thenbsp\Wechat\Bridge\Util;

class PayChoose extends ConfigGenerator
{
    /**
     * 生成配置文件
     */
    public function generateConfig()
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

        return $options;
    }
}

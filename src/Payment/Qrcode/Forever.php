<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Bridge\Util;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Forever extends ArrayCollection
{
    /**
     * 支付地址
     */
    const PAYMENT_URL = 'weixin://wxpay/bizpayurl';

    /**
     * 商户 KEY
     */
    protected $key;

    /**
     * 构造方法
     */
    public function __construct($appid, $mchid, $key)
    {
        $this->key = $key;

        $this->set('appid',     $appid);
        $this->set('mch_id',    $mchid);
    }

    /**
     * 获取支付链接
     */
    public function getPayurl($productId, array $defaults = array())
    {
        $defaultOptions = array(
            'appid'             => $this['appid'],
            'mch_id'            => $this['mch_id'],
            'time_stamp'        => Util::getTimestamp(),
            'nonce_str'         => Util::getRandomString(),
        );

        $options                = array_replace($defaultOptions, $defaults);
        $options['product_id']  = $productId;

        // 按 ASCII 码排序
        ksort($options);

        $signature = urldecode(http_build_query($options));
        $signature = strtoupper(md5($signature.'&key='.$this->key));

        $options['sign'] = $signature;
        
        $query = http_build_query($options);

        return self::PAYMENT_URL.'?'.urlencode($query);
    }
}

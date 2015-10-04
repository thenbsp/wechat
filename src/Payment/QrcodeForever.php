<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\OptionValidator;

/**
 * 微信扫码支付之永久模式（模式一）
 */
class QrcodeForever
{
    /**
     * 支付地址
     */
    const PAYMENT_URL = 'weixin://wxpay/bizpayurl';

    /**
     * 订单选项
     */
    protected $options;

    /**
     * 全部选项（不包括 sign）
     */
    protected $required = array('appid', 'mch_id', 'time_stamp', 'nonce_str', 'product_id');

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $options)
    {
        if( !$wechat->offsetExists('mchid') ) {
            throw new \InvalidArgumentException('The required options "mchid" are missing.');
        }

        if( !$wechat->offsetExists('mchkey') ) {
            throw new \InvalidArgumentException('The required options "mchkey" are missing.');
        }

        $defaults = array(
            'appid'             => $wechat['appid'],
            'mch_id'            => $wechat['mchid'],
            'time_stamp'        => time(),
            'nonce_str'         => uniqid()
        );

        $validator = new OptionValidator();
        $validator
            ->setDefined($this->required)
            ->setRequired($this->required)
            ->setDefaults($defaults);

        $options = $validator->validate($options);

        // 按 ASCII 码排序
        ksort($options);

        $signature = http_build_query($options);
        $signature = urldecode($signature);
        $signature = strtoupper(md5($signature.'&key='.$wechat['mchkey']));

        $options['sign'] = $signature;

        $this->options = $options;
    }

    /**
     * 获取选项
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * 获取支付 URL
     */
    public function getPayurl()
    {
        $query = http_build_query($this->options);

        return self::PAYMENT_URL.'?'.urlencode($query);
    }
}

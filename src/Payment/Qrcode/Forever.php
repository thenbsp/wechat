<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Forever extends ArrayCollection implements PayurlInterface
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
     * 全部选项（不包括 sign）
     */
    protected $required = array('appid', 'mch_id', 'time_stamp', 'nonce_str', 'product_id');

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
    public function getPayurl()
    {
        $options = $this->resolveOptions();

        // 按 ASCII 码排序
        ksort($options);

        $signature = urldecode(http_build_query($options));
        $signature = strtoupper(md5($signature.'&key='.$this->key));

        $options['sign'] = $signature;
        
        $query = http_build_query($options);

        return self::PAYMENT_URL.'?'.urlencode($query);
    }

    /**
     * 合并和校验参数
     */
    public function resolveOptions()
    {
        $defaults = array(
            'appid'             => $this['appid'],
            'mch_id'            => $this['mch_id'],
            'time_stamp'        => Util::getTimestamp(),
            'nonce_str'         => Util::getRandomString(),
        );

        $resolver = new OptionsResolver();
        $resolver
            ->setDefined($this->required)
            ->setRequired($this->required)
            ->setDefaults($defaults);

        return $resolver->resolve($this->toArray());
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getPayurl();
    }
}

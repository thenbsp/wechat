<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Http;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Unifiedorder extends ArrayCollection
{
    /**
     * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
     */
    const UNIFIEDORDER = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    /**
     * 商户 KEY
     */
    protected $key;

    /**
     * 有效的 trade_type 类型
     */
    protected $tradeTypes = array('JSAPI', 'NATIVE', 'APP', 'WAP');

    /**
     * 全部选项（不包括 sign）
     */
    protected $defined = array(
        'appid', 'mch_id', 'device_info', 'nonce_str', 'body',
        'detail','attach', 'out_trade_no', 'fee_type', 'total_fee',
        'spbill_create_ip', 'time_start', 'time_expire', 'goods_tag',
        'notify_url', 'trade_type', 'product_id', 'limit_pay', 'openid'
    );

    /**
     * 必填选项（不包括 sign）
     */
    protected $required = array(
        'appid', 'mch_id', 'nonce_str', 'body', 'out_trade_no',
        'total_fee', 'spbill_create_ip', 'notify_url', 'trade_type'
    );

    /**
     * 构造方法
     */
    public function __construct($appid, $mchid, $key)
    {
        $this->key = $key;

        $this->set('appid', $appid);
        $this->set('mch_id', $mchid);
    }

    /**
     * 获取商户 Key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * 获取响应结果
     */
    public function getResponse()
    {
        $options = $this->resolveOptions();

        // 按 ASCII 码排序
        ksort($options);

        $signature = urldecode(http_build_query($options));
        $signature = strtoupper(md5($signature.'&key='.$this->key));

        $options['sign'] = $signature;

        $response = Http::request('POST', static::UNIFIEDORDER)
            ->withXmlBody($options)
            ->send();

        if( $response['return_code'] === 'FAIL' ) {
            throw new \Exception($response['return_msg']);
        }

        if( $response['result_code'] === 'FAIL' ) {
            throw new \Exception($response['err_code_des']);
        }

        return $response;
    }

    /**
     * 合并和校验参数
     */
    public function resolveOptions()
    {
        $normalizer = function($options, $value) {
            if( ($value === 'JSAPI') && !isset($options['openid']) ) {
                throw new \InvalidArgumentException(sprintf(
                    '订单的 trade_type 为 “%s” 时，必需指定 “openid” 字段', $value));
            }
            return $value;
        };

        $defaults = array(
            'trade_type'        => current($this->tradeTypes),
            'spbill_create_ip'  => Util::getClientIp(),
            'nonce_str'         => Util::getRandomString(),
        );

        $resolver = new OptionsResolver();
        $resolver
            ->setDefined($this->defined)
            ->setRequired($this->required)
            ->setAllowedValues('trade_type', $this->tradeTypes)
            ->setNormalizer('trade_type', $normalizer)
            ->setDefaults($defaults);

        return $resolver->resolve($this->toArray());
    }
}

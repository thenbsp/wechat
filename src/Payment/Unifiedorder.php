<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Util\OptionValidator;

class Unifiedorder
{
    /**
     * 统一下单接口
     * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
     */
    const UNIFIEDORDER_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    /**
     * Wechat 对象
     */
    protected $wechat;

    /**
     * 订单选项
     */
    protected $options;

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
     * 必填项目（不包括 sign）
     */
    protected $required = array(
        'appid', 'mch_id', 'nonce_str', 'body', 'out_trade_no',
        'total_fee', 'spbill_create_ip', 'notify_url', 'trade_type'
    );

    /**
     * 有效的 trade_type 类型
     */
    protected $tradeType = array('JSAPI', 'NATIVE', 'APP', 'WAP');

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $options)
    {
        if( !$wechat->offsetExists('mchid') ) {
            throw new \InvalidArgumentException('The required options "mch_id" are missing.');
        }

        if( !$wechat->offsetExists('mchkey') ) {
            throw new \InvalidArgumentException('The required options "mch_key" are missing.');
        }


        $normalizer = function($options, $value) {
            if( ($value === 'JSAPI') && !isset($options['openid']) ) {
                throw new \InvalidArgumentException('The required options "openid" are missing.');
            }
            return $value;
        };

        $defaults = array(
            'appid'             => $wechat['appid'],
            'mch_id'            => $wechat['mchid'],
            'spbill_create_ip'  => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
            'trade_type'        => current($this->tradeType),
            'nonce_str'         => uniqid(),
        );

        $validator = new OptionValidator();
        $validator
            ->setDefined($this->defined)
            ->setRequired($this->required)
            ->setAllowedValues('trade_type', $this->tradeType)
            ->setNormalizer('trade_type', $normalizer)
            ->setDefaults($defaults);

        $validated = $validator->validate($options);

        $this->wechat   = $wechat;
        $this->options  = $validated;
    }

    /**
     * 获取 Wechat 对象
     */
    public function getWechat()
    {
        return $this->wechat;
    }

    /**
     * 获取订单选项
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * 获取下单结果
     */
    public function getResponse()
    {
        ksort($this->options);

        $signature = http_build_query($this->options);
        $signature = urldecode($signature);
        $signature = strtoupper(md5($signature.'&key='.$this->wechat['mchkey']));

        $this->options['sign'] = $signature;

        $request = Http::post(self::UNIFIEDORDER_URL, array(
            'body' => Serialize::encode($this->options, 'xml')
        ));

        $response = (array) $request->xml(array(
            'libxml_options' => LIBXML_NOCDATA
        ));

        if( array_key_exists('result_code', $response) &&
            ($response['result_code'] === 'FAIL') ) {
            throw new \Exception($response['err_code'].': '.$response['err_code_des']);
        }

        if( array_key_exists('return_code', $response) &&
            $response['return_code'] === 'FAIL' ) {
            throw new \Exception($response['return_code'].': '.$response['return_msg']);
        }

        return $response;
    }
}

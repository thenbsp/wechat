<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Options;
use Thenbsp\Wechat\Util\Serialize;

class Unifiedorder extends Options
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

        $this->wechat = $wechat;
        parent::__construct($options);
    }

    /**
     * 获取下单结果
     */
    public function getResponse()
    {
        // 生成签名
        $options = $this->getOptions();

        ksort($options);

        $signature = http_build_query($options);
        $signature = urldecode($signature);
        $signature = strtoupper(md5($signature.'&key='.$this->wechat['mchkey']));

        $options['sign'] = $signature;

        $request = Http::post(self::UNIFIEDORDER_URL, array(
            'body' => Serialize::encode($options, 'xml')
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

    /**
     * 配置选项
     */
    protected function configureOptions($resolver)
    {
        $normalizer = function($options, $value) {
            // trade_type 为 JSAPI 时，openid 必填
            if( ($value === 'JSAPI') && !isset($options['openid']) ) {
                throw new \InvalidArgumentException('The required options "openid" are missing.');
            }
            // trade_type 为 NATIVE 时，product_id 必填
            // if( ($value === 'NATIVE') && !isset($options['product_id']) ) {
            //     throw new MissingOptionsException('The required options "product_id" are missing.');
            // }
            return $value;
        };

        $defaults = array(
            'appid'             => $this->wechat['appid'],
            'mch_id'            => $this->wechat['mchid'],
            'spbill_create_ip'  => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
            'trade_type'        => current($this->tradeType),
            'nonce_str'         => uniqid(),
        );

        $resolver
            ->setDefined($this->defined)
            ->setRequired($this->required)
            ->setAllowedValues('trade_type', $this->tradeType)
            ->setNormalizer('trade_type', $normalizer)
            ->setDefaults($defaults);
    }
}

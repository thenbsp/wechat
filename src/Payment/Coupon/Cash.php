<?php

namespace Thenbsp\Wechat\Payment\Coupon;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Util\OptionValidator;

class Cash
{
    /**
     * 现金红包接口地址
     */
    const COUPON_CASH_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

    /**
     * Wechat 对象
     */
    protected $wechat;

    /**
     * 请求参数
     */
    protected $options;

    /**
     * 全部选项（不包括 sign）
     */
    protected $required = array(
        'nonce_str', 'mch_billno', 'mch_id', 'wxappid', 'send_name', 're_openid',
        'total_amount', 'total_num', 'wishing', 'client_ip', 'act_name', 'remark'
    );

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

        if( !$wechat->offsetExists('authenticate_cert') ) {
            throw new \InvalidArgumentException('The required options "authenticate_cert" are missing.');
        }

        $defaults = array(
            'wxappid'       => $wechat['appid'],
            'mch_id'        => $wechat['mchid'],
            'nonce_str'     => uniqid(),
            'client_ip'     => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0'
        );

        $validator = new OptionValidator();
        $validator
            ->setDefined($this->required)
            ->setRequired($this->required)
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
     * 获取响应结果
     */
    public function getResponse()
    {
        ksort($this->options);

        $signature = http_build_query($this->options);
        $signature = urldecode($signature);
        $signature = strtoupper(md5($signature.'&key='.$this->wechat['mchkey']));

        $this->options['sign'] = $signature;

        $request = Http::post(self::COUPON_CASH_URL, array(
            'body' => Serialize::encode($this->options, 'xml'),
            'cert' => $this->wechat['authenticate_cert']['cert'],
            'ssl_key' => $this->wechat['authenticate_cert']['key']
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

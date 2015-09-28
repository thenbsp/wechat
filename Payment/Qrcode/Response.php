<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Util\Serialize;

class Response extends Unifiedorder
{
    /**
     * 状态（成功）
     */
    const SUCCESS = 'SUCCESS';

    /**
     * 状态（失败）
     */
    const FAILURE = 'FAIL';

    /**
     * 输出选项
     */
    protected $options = array();

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $optionsOfUnifiedorder)
    {
        parent::__construct($wechat, $optionsOfUnifiedorder);

        $response = $this->getResponse();

        $options = array(
            'appid'         => $wechat['appid'],
            'mch_id'        => $wechat['mchid'],
            'prepay_id'     => $response['prepay_id'],
            'nonce_str'     => uniqid(),
            'return_code'   => self::SUCCESS,
            'result_code'   => self::SUCCESS
        );

        ksort($options);

        $signature = http_build_query($options);
        $signature = urldecode($signature);
        $signature = strtoupper(md5($signature.'&key='.$wechat['mchkey']));

        $options['sign'] = $signature;

        $this->options = $options;
    }

    /**
     * 发送响应
     */
    public function send()
    {
        self::_finalOutput($this->options);
    }

    /**
     * 
     */
    public static function fail($errorMessage)
    {
        $response = array('return_code' => self::FAILURE);

        if( !is_null($errorMessage) ) {
            $response['return_msg'] = $errorMessage;
        }

        self::_finalOutput($response);
    }

    /**
     * 最终输出
     */
    private static function _finalOutput(array $arrayResponse)
    {
        header("Content-Type: application/xml; charset=utf-8");
        echo Serialize::encode($arrayResponse, 'xml');
        exit;
    }
}

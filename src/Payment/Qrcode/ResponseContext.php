<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Symfony\Component\HttpFoundation\Response;

class ResponseContext
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
     * 成功响应
     */
    public static function success(Unifiedorder $unifiedorder)
    {
        $unifiedorder->set('trade_type', 'NATIVE');

        $response = $unifiedorder->getResponse();

        $options = array(
            'appid'         => $unifiedorder['appid'],
            'mch_id'        => $unifiedorder['mch_id'],
            'prepay_id'     => $response['prepay_id'],
            'nonce_str'     => Util::getRandomString(),
            'return_code'   => self::SUCCESS,
            'result_code'   => self::SUCCESS
        );

        // 按 ASCII 码排序
        ksort($options);

        $signature = urldecode(http_build_query($options));
        $signature = strtoupper(md5($signature.'&key='.$unifiedorder->getKey()));

        $options['sign'] = $signature;

        static::finalResponse($options);
    }

    /**
     * 失败响应
     */
    public static function fail($errorMessage = null)
    {
        $options = array('return_code' => self::FAILURE);

        if( !is_null($errorMessage) ) {
            $options['return_msg'] = $errorMessage;
        }

        static::finalResponse($options);
    }

    /**
     * 最终输出
     */
    public static function finalResponse(array $options)
    {
        $content = Serializer::xmlEncode($options);
        $headers = array('Content-Type'=>'application/xml');

        $response = new Response($content, 200, $headers);
        $response->send();
    }
}

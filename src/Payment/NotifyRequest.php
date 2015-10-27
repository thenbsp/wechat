<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\Request;
use Thenbsp\Wechat\Util\Response;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Util\Option;
use Thenbsp\Wechat\Util\OptionValidator;

class NotifyRequest extends Option
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
     * 请求对象
     */
    protected $request;

    /**
     * 消误消息
     */
    protected $error;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $request = Request::createFromGlobals();
        $content = $request->getContent();

        if( !empty($content) ) {
            $options = Serialize::decode($content, 'xml');
            $this->setOptions($options);
        }

        $this->request = $request;
    }

    /**
     * 获取请求对象
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * 获取请求原内容
     */
    public function getContent()
    {
        return $this->request->getContent();
    }

    /**
     * 获取错误消息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 检测本次请求是有有效
     */
    public function isValid()
    {
        if( empty($this->options) ) {
            $this->error = 'Invalid Request';
            return false;
        }

        $required = array(
            'appid', 'bank_type', 'cash_fee', 'fee_type', 'is_subscribe', 'mch_id',
            'nonce_str', 'openid', 'out_trade_no', 'result_code', 'return_code',
            'sign', 'time_end', 'total_fee', 'trade_type', 'transaction_id'
        );

        $validator = new OptionValidator();
        $validator
            ->setRequired($required);

        try {
            $validator->validate($this->options);
        } catch (\InvalidArgumentException $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * 成功响应
     */
    public static function success($errorMessage = null)
    {
        $response = array('return_code' => self::SUCCESS);

        if( !is_null($errorMessage) ) {
            $response['return_msg'] = $errorMessage;
        }

        self::_finalResponse($response);
    }

    /**
     * 失败响应
     */
    public static function fail($errorMessage = null)
    {
        $response = array('return_code' => self::FAILURE);

        if( !is_null($errorMessage) ) {
            $response['return_msg'] = $errorMessage;
        }

        self::_finalResponse($response);
    }

    /**
     * 最终输出
     */
    private static function _finalResponse(array $arrayResponse)
    {
        $headers = array(
            'Content-Type' => 'application/xml'
        );

        $response = new Response();
        $response->setHeaders($headers);
        $response->setContent(Serialize::encode($arrayResponse, 'xml'));
        $response->send();
        exit;
    }
}

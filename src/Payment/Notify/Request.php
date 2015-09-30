<?php

namespace Thenbsp\Wechat\Payment\Notify;

use Thenbsp\Wechat\Util\Options;
use Thenbsp\Wechat\Util\Serialize;

class Request extends Options
{
    /**
     * 请求原始内容 rawContent
     */
    protected $content;

    /**
     * 检测是否有效
     */
    protected $valid;

    /**
     * 如果检测失败，则包含错误消息
     */
    protected $error;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->content = file_get_contents('php://input');

        if( !empty($this->content) ) {
            $data = Serialize::decode($this->content, 'xml');
            try {
                parent::__construct($data);
                $this->valid = true;
            } catch (\InvalidArgumentException $e) {
                $this->error = $e->getMessage();
            }
        }
    }

    /**
     * 获取请求数据
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 检测是否有效
     */
    public function isValid()
    {
        return (true === $this->valid);
    }

    /**
     * 获取错误消息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 配置选项
     */
    protected function configureOptions($resolver)
    {
        $defined = array(
            'appid', 'bank_type', 'cash_fee', 'fee_type', 'is_subscribe', 'mch_id',
            'nonce_str', 'openid', 'out_trade_no', 'result_code', 'return_code',
            'sign', 'time_end', 'total_fee', 'trade_type', 'transaction_id'
        );

        $resolver
            ->setDefined($defined)
            ->setRequired($defined);
    }
}

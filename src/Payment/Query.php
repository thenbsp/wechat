<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Http;
use Doctrine\Common\Collections\ArrayCollection;

class Query extends ArrayCollection
{
    /**
     * 订单查询接口
     */
    const QUERY = 'https://api.mch.weixin.qq.com/pay/orderquery';

    /**
     * 商户 KEY
     */
    protected $key;

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
     * 根据 transaction_id 查询
     */
    public function fromTransactionId($transactionId)
    {
        return $this->doQuery(array('transaction_id'=>$transactionId));
    }

    /**
     * 根据 out_trade_no 查询
     */
    public function fromOutTradeNo($outTradeNo)
    {
        return $this->doQuery(array('out_trade_no'=>$outTradeNo));
    }

    /**
     * 查询订单
     */
    public function doQuery(array $by)
    {
        $options                = array_merge($this->toArray(), $by);
        $options['nonce_str']   = Util::getRandomString();

        // 按 ASCII 码排序
        ksort($options);

        $signature = urldecode(http_build_query($options));
        $signature = strtoupper(md5($signature.'&key='.$this->key));

        $options['sign'] = $signature;

        $response = Http::request('POST', static::QUERY)
            ->withXmlBody($options)
            ->send();

        if( $response['result_code'] === 'FAIL' ) {
            throw new \Exception($response['err_code_des']);
        }

        if( $response['return_code'] === 'FAIL' ) {
            throw new \Exception($response['return_msg']);
        }

        return $response;
    }
}

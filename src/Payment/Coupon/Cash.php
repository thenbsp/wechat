<?php

namespace Thenbsp\Wechat\Payment\Coupon;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Http;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Cash extends ArrayCollection
{
    /**
     * 现金红包接口地址
     */
    const COUPON_CASH = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

    /**
     * 商户 KEY
     */
    protected $key;

    /**
     * SSL 证书
     */
    protected $sslCert;
    protected $sslKey;

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
    public function __construct($appid, $mchid, $key)
    {
        $this->key = $key;

        $this->set('wxappid',   $appid);
        $this->set('mch_id',    $mchid);
    }

    /**
     * 调置 SSL 证书
     */
    public function setSSLCert($sslCert, $sslKey)
    {
        if( !file_exists($sslCert) ) {
            throw new \InvalidArgumentException(sprintf('File "%s" Not Found', $sslCert));
        }

        if( !file_exists($sslKey) ) {
            throw new \InvalidArgumentException(sprintf('File "%s" Not Found', $sslKey));
        }

        $this->sslCert = $sslCert;
        $this->sslKey  = $sslKey;
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

        $response = Http::request('POST', static::COUPON_CASH)
            ->withSSLCert($this->sslCert, $this->sslKey)
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
        $defaults = array(
            'nonce_str' => Util::getRandomString(),
            'client_ip' => Util::getClientIp()
        );

        $resolver = new OptionsResolver();
        $resolver
            ->setDefined($this->required)
            ->setRequired($this->required)
            ->setDefaults($defaults);

        return $resolver->resolve($this->toArray());
    }
}

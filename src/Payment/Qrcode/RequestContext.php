<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Bridge\XmlResponse;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class RequestContext extends ArrayCollection
{
    /**
     * 状态（成功）
     */
    const SUCCESS = 'SUCCESS';

    /**
     * 状态（失败）
     */
    const FAIL = 'FAIL';

    /**
     * Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * 构造方法
     */
    public function __construct(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();

        $this->setRequest($request);
    }

    /**
     * 设置请求对象
     */
    public function setRequest(Request $request)
    {
        $content = $request->getContent();

        $options = ($content && Serializer::isXML($content))
            ? Serializer::xmlDecode($content)
            : array();

        // update ArrayCollection options
        parent::__construct($options);

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
     * 检测本次请求是否有效
     */
    public function isValid()
    {
        return $this->containsKey('product_id');
    }

    /**
     * 错误响应
     */
    public function fail($errorMessage = null)
    {
        $options = array('return_code' => self::FAIL);

        if( !is_null($errorMessage) ) {
            $options['return_msg'] = $errorMessage;
        }

        $this->xmlResponse($options);
    }

    /**
     * 成功响应
     */
    public function success(Unifiedorder $unifiedorder)
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

        $this->xmlResponse($options);
    }

    /**
     * 响应 Xml
     */
    private function xmlResponse(array $options)
    {
        $response = new XmlResponse($options);
        $response->send();
    }
}

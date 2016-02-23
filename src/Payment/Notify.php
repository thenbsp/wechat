<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Bridge\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

class Notify extends ArrayCollection
{
    /**
     * 成功
     */
    const SUCCESS = 'SUCCESS';

    /**
     * 失败
     */
    const FAIL = 'FAIL';

    /**
     * 构造方法
     */
    public function __construct(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();
        $content = $request->getContent();

        try {
            $options = Serializer::parse($content);
        } catch (\InvalidArgumentException $e) {
            $options = array();
        }

        parent::__construct($options);
    }

    /**
     * 错误响应
     */
    public function fail($message = null)
    {
        $options = array('return_code' => self::FAIL);

        if( !is_null($message) ) {
            $options['return_msg'] = $message;
        }

        $this->xmlResponse($options);
    }

    /**
     * 成功响应
     */
    public function success($message = null)
    {
        $options = array('return_code' => self::SUCCESS);

        if( !is_null($message) ) {
            $options['return_msg'] = $message;
        }

        $this->xmlResponse($options);
    }

    /**
     * 响应 Xml
     */
    protected function xmlResponse(array $options)
    {
        $content = Serializer::xmlEncode($options);
        $headers = array('Content-Type'=>'application/xml');

        $response = new Response($content, 200, $headers);
        $response->send();
    }
}

<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Bridge\XmlResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class Notify extends ArrayCollection
{
    /**
     * 成功
     */
    const SUCCESS = 'SUCCESS';

    /**
     * 失败.
     */
    const FAIL = 'FAIL';

    /**
     * 构造方法.
     */
    public function __construct(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();
        $content = $request->getContent();

        try {
            $options = Serializer::parse($content);
        } catch (\InvalidArgumentException $e) {
            $options = [];
        }

        parent::__construct($options);
    }

    /**
     * 错误响应.
     */
    public function fail($message = null)
    {
        $options = ['return_code' => self::FAIL];

        if (null !== $message) {
            $options['return_msg'] = $message;
        }

        $this->xmlResponse($options);
    }

    /**
     * 成功响应.
     */
    public function success($message = null)
    {
        $options = ['return_code' => self::SUCCESS];

        if (null !== $message) {
            $options['return_msg'] = $message;
        }

        $this->xmlResponse($options);
    }

    /**
     * 响应 Xml.
     */
    protected function xmlResponse(array $options)
    {
        $response = new XmlResponse($options);
        $response->send();
    }
}

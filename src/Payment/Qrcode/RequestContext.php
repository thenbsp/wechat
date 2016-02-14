<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Bridge\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class RequestContext extends ArrayCollection
{
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
        $content = $request->getContent();

        if( empty($content) ) {
            throw new \InvalidArgumentException('Invalid Request');
        }

        $options = Serializer::xmlDecode($content);

        if( !array_key_exists('product_id', $options) ) {
            throw new \InvalidArgumentException('Invalid Request');
        }

        parent::__construct($options);
    }

    /**
     * 获取 Request 对象
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * 获取 Request 内容
     */
    public function getContent()
    {
        return $this->request->getContent();
    }
}
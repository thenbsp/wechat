<?php

namespace Thenbsp\Wechat\Bridge;

use Symfony\Component\HttpFoundation\Response;

class XmlResponse extends Response
{
    /**
     * 构造方法.
     */
    public function __construct(array $options, array $headers = [], $status = 200)
    {
        $content = Serializer::xmlEncode($options);
        $headers = array_replace($headers, ['Content-Type' => 'application/xml']);

        parent::__construct($content, $status, $headers);
    }
}

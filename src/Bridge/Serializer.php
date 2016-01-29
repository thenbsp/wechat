<?php

namespace Thenbsp\Wechat\Bridge;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class Serializer
{
    /**
     * Symfony\Component\Serializer\Encoder\XmlEncoder
     */
    protected $xmlEncoder;

    /**
     * Symfony\Component\Serializer\Encoder\JsonEncoder
     */
    protected $jsonEncoder;

    /**
     * initialize
     */
    public function __construct()
    {
        $this->xmlEncoder   = new XmlEncoder;
        $this->jsonEncoder  = new JsonEncoder;
    }

    /**
     * json encode
     */
    public function jsonEncode($data, array $context = array())
    {
        $defaults = array(
            'json_encode_options' => defined('JSON_UNESCAPED_UNICODE')
                ? JSON_UNESCAPED_UNICODE
                : 0
        );

        return $this->jsonEncoder->encode($data, 'json', array_replace($defaults, $context));
    }

    /**
     * json decode
     */
    public function jsonDecode($data, array $context = array())
    {
        $defaults = array(
            'json_decode_associative'       => true,
            'json_decode_recursion_depth'   => 512,
            'json_decode_options'           => 0,
        );

        return $this->jsonEncoder->decode($data, 'json', array_replace($defaults, $context));
    }

    /**
     * xml encode
     */
    public function xmlEncode($data, array $context = array())
    {
        $defaults = array(
            'xml_root_node_name'    => 'response',
            'xml_format_output'     => 'formatOutput',
            'xml_version'           => 'xmlVersion',
            'xml_encoding'          => 'utf-8',
            'xml_standalone'        => 'xmlStandalone',
        );

        return $this->xmlEncoder->encode($data, 'xml', array_replace($defaults, $context));
    }

    /**
     * xml decode
     */
    public function xmlDecode($data, array $context = array())
    {
        return $this->xmlEncoder->decode($data, 'xml', $context);
    }
}

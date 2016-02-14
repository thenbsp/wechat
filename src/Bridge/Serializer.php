<?php

namespace Thenbsp\Wechat\Bridge;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class Serializer
{
    /**
     * json encode
     */
    public static function jsonEncode($data, array $context = array())
    {
        $defaults = array(
            'json_encode_options' => defined('JSON_UNESCAPED_UNICODE')
                ? JSON_UNESCAPED_UNICODE
                : 0
        );

        return (new JsonEncoder)->encode($data, 'json', array_replace($defaults, $context));
    }

    /**
     * json decode
     */
    public static function jsonDecode($data, array $context = array())
    {
        $defaults = array(
            'json_decode_associative'       => true,
            'json_decode_recursion_depth'   => 512,
            'json_decode_options'           => 0,
        );

        return (new JsonEncoder)->decode($data, 'json', array_replace($defaults, $context));
    }

    /**
     * xml encode
     */
    public static function xmlEncode($data, array $context = array())
    {
        $defaults = array(
            'xml_root_node_name'    => 'xml',
            'xml_format_output'     => 'formatOutput',
            'xml_version'           => 'xmlVersion',
            'xml_encoding'          => 'utf-8',
            'xml_standalone'        => 'xmlStandalone',
        );

        return (new XmlEncoder)->encode($data, 'xml', array_replace($defaults, $context));
    }

    /**
     * xml decode
     */
    public static function xmlDecode($data, array $context = array())
    {
        return (new XmlEncoder)->decode($data, 'xml', $context);
    }
}

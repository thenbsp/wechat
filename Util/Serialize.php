<?php

namespace Thenbsp\Wechat\Util;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Exception\UnsupportedException;

// PHP 5.4 之前没有 JSON_UNESCAPED_UNICODE 选项
defined('JSON_UNESCAPED_UNICODE') || define('JSON_UNESCAPED_UNICODE', 0);

class Serialize
{
    /**
     * Serialize instance
     */
    protected static $instance;

    /**
     * getInstance
     */
    public static function getInstance()
    {
        if( is_null(self::$instance) ) {
            self::$instance = new Serializer(
                array(new ObjectNormalizer),
                array(new XmlEncoder, new JsonEncoder)
            );
        }

        return self::$instance;
    }

    /**
     * 检测是否为支持的格式
     */
    public static function isSupportType($type)
    {
        return in_array(strtolower($type), array('json', 'xml'));
    }

    /**
     * 序列化
     */
    public static function encode(array $data, $type, $options = array() )
    {
        $type = strtolower($type);

        if( !self::isSupportType($type) ) {
            throw new UnsupportedException(sprintf('Invalid format: %s', $type));
        }

        $defaultOptions = array(
            'xml'   => array('xml_root_node_name'=>'xml', 'xml_encoding'=>'utf-8'),
            'json'  => array('json_encode_options'=>JSON_UNESCAPED_UNICODE)
        );

        $defaults = array_key_exists($type, $defaultOptions) ?
            $defaultOptions[$type] : array();

        return self::getInstance()->encode($data, $type, array_replace($defaults, $options));
    }

    /**
     * 反序列化
     */
    public static function decode($data, $type)
    {
        $type = strtolower($type);

        if( !self::isSupportType($type) ) {
            throw new UnsupportedException(sprintf('Invalid format: %s', $type));
        }

        return self::getInstance()->decode($data, $type);
    }
}

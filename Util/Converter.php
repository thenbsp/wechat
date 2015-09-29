<?php

namespace Thenbsp\Wechat\Util;

class Converter
{
    /**
     * 检测是否为 XML String
     */
    public static function isXML($data)
    {
        $xml = @simplexml_load_string($data);

        return ($xml instanceof SimpleXmlElement);
    }

    /**
     * 检测是否为 JSON String
     */
    public static function isJSON($data)
    {
        return (@json_decode($data) !== null);
    }

    /**
     * 检测是否为 Serialize String
     */
    public static function isSerialized($data)
    {
        return (@unserialize($data) !== false);
    }
}

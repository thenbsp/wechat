<?php

namespace Thenbsp\Wechat\Util;

/**
 * Util
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/07/28
 */
class Util
{
    /**
     * 获取当前 URL（不包含 “#” 号之后）
     */
    public static function currentUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
            ? 'https://' : 'http://';

        return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    /**
     * 获取随机字符串
     */
    public static function randomString($length = 16)
    {
        $characters         = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength   = strlen($characters);
        $randomString       = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }

    /**
     * 获取客户端 IP
     */
    public static function clientIP()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = '0.0.0.0';
        return $ipaddress;
    }

    /**
     * Array 转为 XML
     */
    public static function array2XML($array, $rootNodeName = 'xml', $xml = null)
    {
        if ($xml == null) {
            $xml = simplexml_load_string("<$rootNodeName/>");
        }

        foreach ($array AS $key => $value) {

            is_array($value) ?
                static::array2XML($value, $rootNodeName, $xml->addChild($key)) :
                $xml->addChild($key, $value);
        }
        return $xml->asXML();
    }

    /**
     * XML 转为 Array
     */
    public static function XML2Array($xml)
    {
        return (array) simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
}
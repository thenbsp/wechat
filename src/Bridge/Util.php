<?php

namespace Thenbsp\Wechat\Bridge;

class Util
{
    /**
     * 检测是否为微信中打开
     */
    public static function isWechat()
    {
        return false === strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger');
    }

    /**
     * 获取当前时间缀
     */
    public static function getTimestamp()
    {
        return (string) time();
    }

    /**
     * 获取当前 URL.
     */
    public static function getCurrentUrl()
    {
        $protocol = (isset($_SERVER['HTTPS']) && ('off' !== $_SERVER['HTTPS'] || 443 == $_SERVER['SERVER_PORT']))
            ? 'https://' : 'http://';

        return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    /**
     * 获取客户端 IP.
     */
    public static function getClientIp()
    {
        $headers = function_exists('apache_request_headers')
            ? apache_request_headers()
            : $_SERVER;

        return isset($headers['REMOTE_ADDR']) ? $headers['REMOTE_ADDR'] : '0.0.0.0';
    }

    /**
     * 获取随机字符.
     */
    public static function getRandomString($length = 10)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, ceil($length / strlen($pool)))), 0, $length);
    }
}

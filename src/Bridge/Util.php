<?php

namespace Thenbsp\Wechat\Bridge;

class Util
{
    /**
     * 获取当前时间缀（微信很变态，必需为字符型）
     */
    public static function getTimestamp()
    {
        return (string) time();
    }

    /**
     * 获取当前 URL
     */
    public static function getCurrentUrl()
    {
        $protocol = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443))
            ? 'https://' : 'http://';

        return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
}

<?php

namespace Thenbsp\Wechat\Util;

use GuzzleHttp\Client;

class Http
{
    /**
     *  GuzzleHttp\Client 实例
     */
    private static $instance;

    /**
     * 获取实例
     */
    public static function getInstance()
    {
        if( is_null(self::$instance) ) {
            self::$instance = new Client;
        }

        return self::$instance;
    }

    /**
     * GET 请求
     */
    public static function get($url, array $options = array())
    {
        return self::getInstance()->get($url, $options);
    }

    /**
     * HEAD 请求
     */
    public static function head($url, array $options = array())
    {
        return self::getInstance()->head($url, $options);
    }

    /**
     * DELETE 请求
     */
    public static function delete($url, array $options = array())
    {
        return self::getInstance()->delete($url, $options);
    }

    /**
     * PUT 请求
     */
    public static function put($url, array $options = array())
    {
        return self::getInstance()->put($url, $options);
    }

    /**
     * PATCH 请求
     */
    public static function patch($url, array $options = array())
    {
        return self::getInstance()->put($url, $options);
    }

    /**
     * POST 请求
     */
    public static function post($url, array $options = array())
    {
        return self::getInstance()->post($url, $options);
    }

    /**
     * OPTIONS 请求
     */
    public static function options($url, array $options = array())
    {
        return self::getInstance()->options($url, $options);
    }
}

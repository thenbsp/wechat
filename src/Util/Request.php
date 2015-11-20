<?php

namespace Thenbsp\Wechat\Util;

class Request
{
    /**
     * 请求对像实例
     */
    protected static $instance;

    /**
     * 请求内容
     */
    protected $content;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $postdata = file_get_contents('php://input');

        $this->setContent($postdata);
    }

    /**
     * 创建请求对象
     */
    public static function createFromGlobals()
    {
        if( is_null(self::$instance) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * 设置内容
     */
    public function setContent($content)
    {
        $content = (string) $content;

        if( !empty($content) ) {
            $this->content = $content;
        }
    }

    /**
     * 获取内容
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 克隆对象
     */
    public function __clone()
    {
        throw new \Exception('Unable to clone Request');
    }
}

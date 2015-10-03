<?php

namespace Thenbsp\Wechat\Util;

class Request
{
    /**
     * 请求内容
     */
    protected $content;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setContent(file_get_contents('php://input'));
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
}

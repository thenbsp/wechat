<?php

namespace Thenbsp\Wechat\Util;

class Response
{
    /**
     * 响应头
     */
    protected $headers = array();

    /**
     * 响应内容
     */
    protected $content;

    /**
     * 构造方法
     */
    public function __construct($content = '', array $headers = array())
    {
        $this->setHeaders($headers);
        $this->setContent($content);
    }

    /**
     * 设置内容类型
     */
    public function setHeaders(array $headers)
    {
        if( !empty($headers) ) {
            foreach( $headers AS $k=>$v ) {
                $this->headers[$k] = $v;
            }
        }
    }

    /**
     * 获取内容类型
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * 设置内容
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    /**
     * 获取内容
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 发送响应
     */
    public function send()
    {
        if( !empty($this->headers) ) {
            foreach( $this->headers AS $k=>$v ) {
                header($k.':'.$v);
            }
        }

        echo $this->content;
    }
}

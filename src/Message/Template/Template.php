<?php

namespace Thenbsp\Wechat\Message\Template;

class Template implements TemplateInterface
{
    /**
     * 模板 ID
     */
    protected $id;

    /**
     * 跳转链接
     */
    protected $url;

    /**
     * 用户 Openid
     */
    protected $openid;

    /**
     * 模板参数
     */
    protected $options;

    /**
     * 构造方法
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * 获取模板 ID
     */
    public function getId()
    {
        return $this->id;

        return $this;
    }

    /**
     * 设置链接
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * 获取逻接
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 设置用户 Openid
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * 获取用户 Openid
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * 添加模板参数
     */
    public function add($key, $value, $color = null)
    {
        $array = array('value' => $value);

        if( !is_null($color) ) {
            $array['color'] = $color;
        }

        $this->options[$key] = $array;

        return $this;
    }

    /**
     * 移除模板参数
     */
    public function remove($key)
    {
        if( isset($this->options[$key]) ) {
            unset($this->options[$key]);
        }

        return $this;
    }

    /**
     * 获取请求内容
     */
    public function getRequestBody()
    {
        return array(
            'template_id'   => $this->id,
            'touser'        => $this->openid,
            'url'           => $this->url,
            'data'          => $this->options
        );
    }
}

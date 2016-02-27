<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;

class ArticleItem extends Entity
{
    /**
     * 图文消息标题
     */
    protected $title;

    /**
     * 图文消息描述
     */
    protected $description;

    /**
     * 图片链接
     */
    protected $picUrl;

    /**
     * 点击图文消息跳转链接
     */
    protected $url;

    /**
     * 设置图文消息标题
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * 设置图文消息描述
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * 设置图片链接
     */
    public function setPicUrl($picUrl)
    {
        $this->picUrl = $picUrl;
    }

    /**
     * 设置点击图文消息跳转链接
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * 消息内容
     */
    public function getBody()
    {
        return array(
            'Title'         => $this->title,
            'Description'   => $this->description,
            'PicUrl'        => $this->picUrl,
            'Url'           => $this->url
        );
    }

    /**
     * 消息类型
     */
    public function getType()
    {
        return 'news';
    }
}

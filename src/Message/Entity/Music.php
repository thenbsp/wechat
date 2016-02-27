<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;

class Music extends Entity
{
    /**
     * 音乐标题
     */
    protected $title;

    /**
     * 音乐描述
     */
    protected $description;

    /**
     * 音乐链接
     */
    protected $musicUrl;

    /**
     * 高质量音乐链接
     */
    protected $HQMusicUrl;

    /**
     * 缩略图的媒体id
     */
    protected $thumbMediaId;

    /**
     * 音乐标题
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * 音乐描述
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * 音乐链接
     */
    public function setMusicUrl($musicUrl)
    {
        $this->musicUrl = $musicUrl;
    }

    /**
     * 高质量音乐链接
     */
    public function setHQMusicUrl($HQMusicUrl)
    {
        $this->HQMusicUrl = $HQMusicUrl;
    }

    /**
     * 缩略图的媒体id
     */
    public function setThumbMediaId($thumbMediaId)
    {
        $this->thumbMediaId = $thumbMediaId;
    }

    /**
     * 消息内容
     */
    public function getBody()
    {
        $body = array(
            'Title'         => $this->title,
            'Description'   => $this->description,
            'MusicUrl'      => $this->musicUrl,
            'HQMusicUrl'    => $this->HQMusicUrl,
            'ThumbMediaId'  => $this->thumbMediaId
        );

        return array('Music'=>$body);
    }

    /**
     * 消息类型
     */
    public function getType()
    {
        return 'music';
    }
}

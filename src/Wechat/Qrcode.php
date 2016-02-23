<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\CacheTrait;
use Thenbsp\Wechat\Wechat\AccessToken;
use Thenbsp\Wechat\Wechat\Qrcode\Ticket;

class Qrcode
{
    /**
     * Cache Trait
     */
    use CacheTrait;

    /**
     * 二维码地址
     */
    const QRCODE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode';

    /**
     * Thenbsp\Wechat\Wechat\AccessToken
     */
    protected $accessToken;

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 获取临时二维码
     */
    public function getTemporary($scene, $expire = 2592000)
    {
        $ticket = new Ticket($this->accessToken, Ticket::QR_SCENE, $scene, $expire);

        if( $this->cache ) {
            $ticket->setCache($this->cache);
        }

        return $this->getResourceUrl($ticket);
    }

    /**
     * 获取永久二维码
     */
    public function getForever($scene)
    {
        $type = is_int($scene)
            ? Ticket::QR_LIMIT_SCENE
            : Ticket::QR_LIMIT_STR_SCENE;

        $ticket = new Ticket($this->accessToken, $type, $scene);
        
        if( $this->cache ) {
            $ticket->setCache($this->cache);
        }

        return $this->getResourceUrl($ticket);
    }

    /**
     * 根据 Ticket 创建二维码资源链接
     */
    public function getResourceUrl(Ticket $ticket)
    {
        $query = array('ticket' => $ticket->getTicketString());

        return static::QRCODE_URL.'?'.http_build_query($query);
    }
}

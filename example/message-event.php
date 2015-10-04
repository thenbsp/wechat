<?php

require './example.php';

use Thenbsp\Wechat\Message\Event;
use Thenbsp\Wechat\Message\EventManager;

use Thenbsp\Wechat\Message\Entity\Text;
use Thenbsp\Wechat\Message\Entity\Image;
use Thenbsp\Wechat\Message\Entity\Video;


class Demo
{
    /**
     * 接口地址
     */
    public function run()
    {
        $eventManager = new EventManager();
        $eventManager
            ->on(Event::TEXT,                       array($this, 'text'))
            ->on(Event::IMAGE,                      array($this, 'image'))
            ->on(Event::VOICE,                      array($this, 'voice'))
            ->on(Event::VIDEO,                      array($this, 'video'))
            ->on(Event::SHORTVIDEO,                 array($this, 'shortvideo'))
            ->on(Event::LOCATION,                   array($this, 'location'))
            ->on(Event::LINK,                       array($this, 'link'))
            ->on(Event::EVENT_SUBSCRIBE,            array($this, 'event_subscribe'))
            ->on(Event::EVENT_UNSUBSCRIBE,          array($this, 'event_unsubscribe'))
            ->on(Event::EVENT_QRCODE_SUBSCRIBE,     array($this, 'event_qrcode_subscribe'))
            ->on(Event::EVENT_QRCODE_UNSUBSCRIBE,   array($this, 'event_qrcode_unsubscribe'))
            ->on(Event::EVENT_LOCATION,             array($this, 'event_location'))
            ->on(Event::EVENT_CLICK,                array($this, 'event_click'))
            ->on(Event::EVENT_VIEW,                 array($this, 'event_view'));
    }

    /**
     * 文本消息
     */
    public function text(Event $event)
    {
        // 被动回复消息
        $options = array(
            'ToUserName'    => $event['ToUserName'],
            'FromUserName'  => $event['FromUserName'],
            'CreateTime'    => time(),
            'Content'       => '测试消息'
        );

        return new Text($options);
    }

    /**
     * 图片消息
     */
    public function image(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 语音消息
     */
    public function voice(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 视频消息
     */
    public function video(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 小视频消息
     */
    public function shortvideo(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 地理位置消息
     */
    public function location(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 链接消息
     */
    public function link(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 关注事件
     */
    public function event_subscribe(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 取消关注事件
     */
    public function event_unsubscribe(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 扫描带参数二维码 用户已关注时的事件推送
     */
    public function event_qrcode_subscribe(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 扫描带参数二维码 用户未关注时，进行关注后的事件推送
     */
    public function event_qrcode_unsubscribe(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 上报地理位置事件
     */
    public function event_location(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 自定义菜单事件
     */
    public function event_click(Event $event)
    {
        var_dump($event->getOptions());
    }

    /**
     * 点击菜单跳转链接时的事件推送
     */
    public function event_view(Event $event)
    {
        var_dump($event->getOptions());
    }
}

$demo = new Demo();
$demo->run();

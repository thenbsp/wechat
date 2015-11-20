<?php

require './example.php';

use Thenbsp\Wechat\Message\Event;
use Thenbsp\Wechat\Message\EventManager;
use Thenbsp\Wechat\Message\Entity;

class Demo
{
    /**
     * Thenbsp\Wechat\Message\EventManager
     */
    protected $eventManager;

    /**
     * 构造方法
     * @param Cache        $cache        [description]
     * @param EventManager $eventManager [description]
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * 接口地址
     */
    public function run()
    {
        $this->eventManager
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
            ->on(Event::EVENT_QRCODE_SUBSCRIBED,    array($this, 'event_qrcode_subscribed'))
            ->on(Event::EVENT_LOCATION,             array($this, 'event_location'))
            ->on(Event::EVENT_CLICK,                array($this, 'event_click'))
            ->on(Event::EVENT_VIEW,                 array($this, 'event_view'));
    }

    /**
     * 被动响应文本消息示例
     */
    public function text(Event $event)
    {
        // 处理其它业务逻辑
        // ...

        // 被动回复消息
        $options = array(
            'CreateTime'    => time(),
            'ToUserName'    => $event['FromUserName'],
            'FromUserName'  => $event['ToUserName'],
            'Content'       => $event['Content']
        );

        return new Entity\Text($options);
    }

    /**
     * 图片消息
     */
    public function image(Event $event)
    {
        echo '图片消息';
        var_dump($event->getOptions());
    }

    /**
     * 语音消息
     */
    public function voice(Event $event)
    {
        echo '语音消息';
        var_dump($event->getOptions());
    }

    /**
     * 视频消息
     */
    public function video(Event $event)
    {
        echo '视频消息';
        var_dump($event->getOptions());
    }

    /**
     * 小视频消息
     */
    public function shortvideo(Event $event)
    {
        echo '小视频消息';
        var_dump($event->getOptions());
    }

    /**
     * 地理位置消息
     */
    public function location(Event $event)
    {
        echo '地理位置消息';
        var_dump($event->getOptions());
    }

    /**
     * 链接消息
     */
    public function link(Event $event)
    {
        echo '链接消息';
        var_dump($event->getOptions());
    }

    /**
     * 关注事件
     */
    public function event_subscribe(Event $event)
    {
        echo '关注事件';
        var_dump($event->getOptions());
    }

    /**
     * 取消关注事件
     */
    public function event_unsubscribe(Event $event)
    {
        echo '取消关注事件';
        var_dump($event->getOptions());
    }

    /**
     * 扫描带参数二维码 用户关注
     */
    public function event_qrcode_subscribe(Event $event)
    {
        echo '扫描带参数二维码 用户关注';
        var_dump($event->getOptions());
    }

    /**
     * 扫描带参数二维码 用户已关注，直接进入会话
     */
    public function event_qrcode_subscribed(Event $event)
    {
        echo '扫描带参数二维码 用户已关注，直接进入会话';
        var_dump($event->getOptions());
    }

    /**
     * 上报地理位置事件
     */
    public function event_location(Event $event)
    {
        echo '上报地理位置事件';
        var_dump($event->getOptions());
    }

    /**
     * 自定义菜单事件
     */
    public function event_click(Event $event)
    {
        echo '自定义菜单事件';
        var_dump($event->getOptions());
    }

    /**
     * 点击菜单跳转链接时的事件推送
     */
    public function event_view(Event $event)
    {
        echo '点击菜单跳转链接时的事件推送';
        var_dump($event->getOptions());
    }
}

$demo = new Demo(new EventManager);
$demo->run();

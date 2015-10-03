# 事件管理器

消息事件分为 ``普通消息`` 和  ``事件消息``，用于用户事件处理，消息事件共包含以下 15 种类型：

- 文本消息
- 图像消息
- 语音消息
- 视频消息
- 小视频消息
- 地理位置消息
- 链接消息
- 关注消息 (Event)
- 取消关注消息 (Event)
- 扫描带参数的二维码关注事件 (Event)
- 扫描带参数的二维码未关注事件 (Event)
- 上报地理位置事件 (Event)
- 点击定义菜单拉取消息事件 (Event)
- 点击定义菜单跳转链接事件 (Event)

http://mp.weixin.qq.com/wiki/10/79502792eef98d6e0c6e1739da387346.html

## 事件名称列表

```php
Event::TEXT                      // 文本消息
Event::IMAGE                     // 图像消息
Event::VOICE                     // 语音消息
Event::VIDEO                     // 视频消息
Event::SHORTVIDEO                // 小视频消息
Event::LOCATION                  // 地理位置消息
Event::LINK                      // 链接消息
Event::EVENT_SUBSCRIBE           // 关注消息 (Event)
Event::EVENT_UNSUBSCRIBE         // 取消关注消息 (Event)
Event::EVENT_QRCODE_SUBSCRIBE    // 扫描带参数的二维码关注事件 (Event)
Event::EVENT_QRCODE_UNSUBSCRIBE  // 扫描带参数的二维码未关注事件 (Event)
Event::EVENT_LOCATION            // 上报地理位置事件 (Event)
Event::EVENT_CLICK               // 点击定义菜单拉取消息事件 (Event)
Event::EVENT_VIEW                // 点击定义菜单跳转链接事件 (Event)
```

## 基本用法

```php
use Thenbsp\Wechat\Message\Event;
use Thenbsp\Wechat\Message\EventManager;

$eventManager = new EventManager();
$eventManager->on('eventName', 'eventCallback');
```

## on 方式调用

```php
$eventManager->on(Event::TEXT,                      'eventCallback');
$eventManager->on(Event::IMAGE,                     'eventCallback');
$eventManager->on(Event::VOICE,                     'eventCallback');
$eventManager->on(Event::VIDEO,                     'eventCallback');
$eventManager->on(Event::SHORTVIDEO,                'eventCallback');
$eventManager->on(Event::LOCATION,                  'eventCallback');
$eventManager->on(Event::LINK,                      'eventCallback');
$eventManager->on(Event::EVENT_SUBSCRIBE,           'eventCallback');
$eventManager->on(Event::EVENT_UNSUBSCRIBE,         'eventCallback');
$eventManager->on(Event::EVENT_QRCODE_SUBSCRIBE,    'eventCallback');
$eventManager->on(Event::EVENT_QRCODE_UNSUBSCRIBE,  'eventCallback');
$eventManager->on(Event::EVENT_LOCATION,            'eventCallback');
$eventManager->on(Event::EVENT_CLICK,               'eventCallback');
$eventManager->on(Event::EVENT_VIEW,                'eventCallback');
```

## onXXX 方式调用

```php
$eventManager->onText('eventCallback');
$eventManager->onImage('eventCallback');
$eventManager->onVoice('eventCallback');
$eventManager->onVideo('eventCallback');
$eventManager->onShortvideo('eventCallback');
$eventManager->onLocation('eventCallback');
$eventManager->onLink('eventCallback');
$eventManager->onEventSubscribe('eventCallback');
$eventManager->onEventUnsubscribe('eventCallback');
$eventManager->onEventQrcodeSubscribe('eventCallback');
$eventManager->onEventQrcodeUnsubscribe('eventCallback');
$eventManager->onEventLocation('eventCallback');
$eventManager->onEventClick('eventCallback');
$eventManager->onEventView('eventCallback');
```

## Closure 方式传入回调

```php
$eventManager->on(Event::TEXT, function(Event $event) {
    // ...
});
```

## Method 方式传入回调

```php
class Demo
{
    public function test(Event $event)
    {
        // ...
    }
}

$eventManager->on(Event::TEXT, array(new Demo, 'test'));
```

## 链式调用

```php

$callback = function(Event $event) {
    // ...
}

$eventManager
    ->on(Event::TEXT,               $callback)
    ->on(Event::IMAGE,              $callback)
    ->on(Event::VIDEO,              $callback)
    ->on(Event::EVENT_CLICK,        $callback)
    ->on(Event::EVENT_SUBSCRIBE,    $callback);

```

## 被动回复消息

被动回复消息可在 ```eventCallback``` 中返回消息实体，更多消息实体类型请参考 message-entity.md。

```php
use Thenbsp\Wechat\Message\Entity\Text;

$eventManager->on(Event::TEXT, function(Event $event) {

    // 处理你自己的业务
    // ...

    // 回复文本消息
    $options = array(
        'ToUserName'    => 'toUser',
        'FromUserName'  => 'fromUser',
        'CreateTime'    => 'CreateTime',
        'Content'       => '测试消息'
    );

    return new Text($options);
});

```



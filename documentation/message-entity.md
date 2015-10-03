# 消息实体

消息实体提供消息对象的生成与约束，用于公众号被动回复消息接口，消息实体包含以下 6 种类型：

- 文本消息
- 图像消息
- 语音消息
- 视频消息
- 音乐消息
- 图文消息

http://mp.weixin.qq.com/wiki/14/89b871b5466b19b3efa4ada8e577d45e.html

## 一、文本消息

```php
use Thenbsp\Wechat\Message\Entity\Text;

$options = array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Content'       => '你好'
);

$entity = new Text($options);
```

## 二、图像消息

```php
use Thenbsp\Wechat\Message\Entity\Image;

$options = array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Image'         => array(
        'MediaId'   => 'Image MediaId'
    )
);

$entity = new Image($options);
```

## 三、语音消息

```php
use Thenbsp\Wechat\Message\Entity\Voice;

$options = array(
   'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Voice'         => array(
        'MediaId'   => 'Voice MediaId'
    )
);

$entity = new Voice($options);
```

## 四、视频消息

```php
use Thenbsp\Wechat\Message\Entity\Video;

$options = array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Video'         => array(
        'MediaId'       => 'Video MediaId',
        'Title'         => 'Video Title',
        'Description'   => 'Video Description'
    )
);

$entity = new Video($options);
```

## 五、音乐消息

```php
use Thenbsp\Wechat\Message\Entity\Music;

$options = array(
   'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Music'         => array(
        'Title'         => 'Music Title',
        'Description'   => 'Music Description',
        'MusicUrl'      => 'Music MusicUrl',
        'HQMusicUrl'    => 'Music HQMusicUrl',
        'ThumbMediaId'  => 'Music ThumbMediaId'
    )
);

$entity = new Music($options);
```

## 六、图文消息

```php
use Thenbsp\Wechat\Message\Entity\News;

$options = array(
   'ToUserName'     => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Articles'      => array(
        'item' => array(
            array(
                'Title'         => 'News 1 Title',
                'Description'   => 'News 1 Description',
                'PicUrl'        => 'News 1 Title',
                'Url'           => 'News 1 Title',
            ),
            array(
                'Title'         => 'News 2 Title',
                'Description'   => 'News 2 Description',
                'PicUrl'        => 'News 2 Title',
                'Url'           => 'News 2 Title'
            ),
            array(
                'Title'         => 'News 3 Title',
                'Description'   => 'News 3 Description',
                'PicUrl'        => 'News 3 Title',
                'Url'           => 'News 3 Title'
            )
        )
    )
);

$entity = new News($options);
```

## 查看消息实体参数

```php
var_dump($entity->getOptions());
```

## 查看消息数据

```php
var_dump($entity->getData());
```

## 输出消息实体

```php
$entity->send();
```
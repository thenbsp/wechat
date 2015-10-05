# 自定义菜单 - 创建菜单

创建自定义菜单需要注入 AccessToken，创建菜单前需要先定义菜单。

## 定义菜单

```php
use Thenbsp\Wechat\Menu\Button;
use Thenbsp\Wechat\Menu\ButtonCollection;

// 菜单集合
$buttonCollection   = new ButtonCollection('菜单名称');

// 一级菜单
$button = new \Thenbsp\Wechat\Menu\Button('菜单名称', '菜单类型', '菜单值');
```

菜单的值因菜单类型不同而不同，具体分为 key/url/media_id，菜单类型包括：

- view
- click
- scancode_push
- scancode_waitmsg
- pic_sysphoto
- pic_photo_or_album
- pic_weixin
- location_select
- media_id
- view_limited

一级菜单不能超过 3 个，子菜单不能超过 5 个。

```php
use Thenbsp\Wechat\Menu\Button;
use Thenbsp\Wechat\Menu\ButtonCollection;

// 包含子菜单的按钮
$button1 = new ButtonCollection('菜单一');
$button1->addChild(new Button('点击', 'click', 'key_1'));
$button1->addChild(new Button('打开网页', 'view', 'http://www.163.com/'));
$button1->addChild(new Button('扫码', 'scancode_push', 'key_2'));

// 包含子菜单的按钮
$button2 = new ButtonCollection('菜单二');
$button2->addChild(new Button('系统拍照发图', 'pic_sysphoto', 'key_3'));
$button2->addChild(new Button('拍照或者相册发图', 'pic_photo_or_album', 'key_4'));
$button2->addChild(new Button('微信相册发图', 'pic_weixin', 'key_5'));

// 一级菜单
$button3 = new Button('菜单三', 'location_select', 'key_6');
```

## 创建菜单

```php
use Thenbsp\Wechat\Menu\Create;

$create = new Create($accessToken);
$create->add($button1);
$create->add($button2);
$create->add($button3);

try {
    $create->doCreate();
} catch (Exception $e) {
    exit($e->getMessage());
}

var_dump('菜单已创建成功');
```
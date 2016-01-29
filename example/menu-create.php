<?php

require './example.php';

use Thenbsp\Wechat\Menu\Create;
use Thenbsp\Wechat\Menu\Button;
use Thenbsp\Wechat\Menu\ButtonCollection;
use Thenbsp\Wechat\Menu\Exception\MenuException;

// new Button("菜单名称", "菜单类型", "key/url/media_id");

$button1 = new ButtonCollection('菜单一');
$button1->addChild(new Button('点击', 'click', 'key_1'));
$button1->addChild(new Button('打开网页', 'view', 'http://www.163.com/'));
$button1->addChild(new Button('扫码', 'scancode_push', 'key_2'));

$button2 = new ButtonCollection('菜单二');
$button2->addChild(new Button('系统拍照发图', 'pic_sysphoto', 'key_3'));
$button2->addChild(new Button('拍照或者相册发图', 'pic_photo_or_album', 'key_4'));
$button2->addChild(new Button('微信相册发图', 'pic_weixin', 'key_5'));

$button3 = new Button('菜单三', 'location_select', 'key_6');

/**
 * 创建菜单
 */
$create = new Create($accessToken);
$create->add($button1);
$create->add($button2);
$create->add($button3);

try {
    $create->doCreate();
} catch (MenuException $e) {
    exit($e->getMessage());
}

var_dump('菜单已创建成功');

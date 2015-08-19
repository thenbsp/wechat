<?php

require './config.php';

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Exception\WechatException;

/**
 * 获取微信服务器 IP 列表
 */
$o = new Wechat(APPID, APPSECRET);

try {
    $ip = $o->getServerIp();
} catch (WechatException $e) {
    exit($e->getMessage());
}

print_r($ip);

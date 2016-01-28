<?php

require './example.php';

use Thenbsp\Wechat\Wechat\Qrcode;
use Thenbsp\Wechat\Wechat\Exception\QrcodeException;

$qrcode1 = new Qrcode($accessToken, 'QR_SCENE', 111);
$qrcode2 = new Qrcode($accessToken, 'QR_LIMIT_SCENE', 111);
$qrcode3 = new Qrcode($accessToken, 'QR_LIMIT_STR_SCENE', 'thenbsp');

// 推荐使用缓存（可选）
$qrcode1->setCacheDriver($cacheDriver);
$qrcode2->setCacheDriver($cacheDriver);
$qrcode3->setCacheDriver($cacheDriver);

// 失败时抛出 QrcodeException
try {
    echo sprintf('<h1>临时二维码</h1><img src="%s" />', $qrcode1->getResourceUrl());
    echo '<br />';
    echo sprintf('<h1>永久二维码（int 参数）</h1><img src="%s" />', $qrcode2->getResourceUrl());
    echo '<br />';
    echo sprintf('<h1>永久二维码（str 参数）</h1><img src="%s" />', $qrcode3->getResourceUrl());
} catch (QrcodeException $e) {
    exit($e->getMessage());
}

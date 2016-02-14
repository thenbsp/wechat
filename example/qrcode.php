<?php

require './example.php';

use Thenbsp\Wechat\Wechat\Qrcode;
use Thenbsp\Wechat\Wechat\Exception\QrcodeTicketException;

$qrcode = new Qrcode($accessToken);
// 推荐使用缓存（可选）
$qrcode->setCache($cache);

try {
    // 临时二维码
    $url1 = $qrcode->getTemporary(1113, 3600);
    // 永久二维码（int 参数）
    $url2 = $qrcode->getForever(1113);
    // 永久二维码（str 参数）
    $url3 = $qrcode->getForever('thenbsp');
} catch (QrcodeTicketException $e) {
    exit($e->getMessage());
}

echo sprintf('<h1>临时二维码</h1><img src="%s" />', $url1);
echo '<br />';
echo sprintf('<h1>永久二维码（int 参数）</h1><img src="%s" />', $url2);
echo '<br />';
echo sprintf('<h1>永久二维码（str 参数）</h1><img src="%s" />', $url3);

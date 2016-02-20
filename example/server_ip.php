<?php

require './example.php';

use Thenbsp\Wechat\Wechat\ServerIp;

$serverIp = new ServerIp($accessToken);
// 推荐使用缓存（可选）
$serverIp->setCache($cache);

// 失败时抛出 ServerIpException
try {
    $ips = $serverIp->getIps(600);
} catch (\Exception $e) {
    exit($e->getMessage());
}

var_dump($ips);

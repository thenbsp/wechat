<?php

require './example.php';

use Thenbsp\Wechat\Wechat\ServerIp;
use Thenbsp\Wechat\Wechat\Exception\ServerIpException;

$serverIp = new ServerIp($accessToken);

// 如果需要缓存数据，可调置缓存驱动
// $serverIp->setCacheDriver($cacheDriver);

try {
    $ips = $serverIp->getIps(600);
} catch (ServerIpException $e) {
    exit($e->getMessage());
}

var_dump($ips);

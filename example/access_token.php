<?php

require './config.php';

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Exception\AccessTokenException;

/**
 * 获取众公号 AccessToken
 */
$o = new Wechat(APPID, APPSECRET);

try {
    $accessToken = $o->getAccessToken();
} catch (AccessTokenException $e) {
    exit($e->getMessage());
}

var_dump($accessToken);

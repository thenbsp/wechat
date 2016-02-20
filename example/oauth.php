<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\OAuth\Client;

/**
 * 只能在微信中打开
 */
if ( Util::isWechat() ) {
    exit('请在微信中打开');
}

$client = new Client(APPID, APPSECRET);
$client->setScope('snsapi_userinfo');

if( !isset($_GET['code']) ) {
    header('Location: '.$client->getAuthorizeUrl());
}

// 通换 code 换取 AccessToken，通过 AccessToken 获取用户信息
try {
    $token    = $client->getAccessToken($_GET['code']);
    $userinfo = $client->getUserinfo($token);
} catch (\Exception $e) {
    exit($e->getMessage());
}

echo '<pre>';
var_dump($token->toArray());
var_dump($userinfo->toArray());
echo '</pre>';


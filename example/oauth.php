<?php

require './example.php';

use Thenbsp\Wechat\OAuth\User;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\OAuth\Exception\OAuthUserException;
use Thenbsp\Wechat\OAuth\Exception\AccessTokenException;

$client = new Client(APPID, APPSECRET);
// $client->setScope('snsapi_userinfo');
// $client->setRedirectUri('current url');

if( !isset($_GET['code']) ) {
    header('Location: '.$client->getAuthorizeUrl());
}

// 获取用户 AccessToken
try {
    $accessToken    = $client->getAccessToken($_GET['code']);
    $userinfo       = $client->getUserinfo();
} catch (AccessTokenException $e) {
    exit($e->getMessage());
}

echo '<pre>';
var_dump($accessToken->toArray());
var_dump($userinfo->toArray());
echo '</pre>';


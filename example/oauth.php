<?php

require './example.php';

use Thenbsp\Wechat\OAuth\User;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\OAuth\Exception\OAuthUserException;
use Thenbsp\Wechat\OAuth\Exception\AccessTokenException;

$client = new Client(APPID, APPSECRET);
$client->setScope('snsapi_userinfo');

if( !isset($_GET['code']) ) {
    header('Location: '.$client->getAuthorizeUrl());
}

try {
    $accessToken = $client->getAccessToken($_GET['code']);
} catch (AccessTokenException $e) {
    exit($e->getMessage());
}

echo '<pre>';
var_dump($accessToken->toArray());
echo '</pre>';

try {
    $user = new User($accessToken);
    $info = $user->getProfile();
} catch (OAuthUserException $e) {
    exit($e->getMessage());
}

echo '<pre>';
var_dump($info);
echo '</pre>';

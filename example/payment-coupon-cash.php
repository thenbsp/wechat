<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\Payment\Coupon\Cash;

/**
 * 只能在微信中打开
 */
if ( Util::isWechat() ) {
    exit('请在微信中打开');
}

/**
 * 获取用户 openid
 */
if( !isset($_SESSION['openid']) ) {

    $client = new Client(APPID, APPSECRET);

    if( !isset($_GET['code']) ) {
        header('Location: '.$client->getAuthorizeUrl());
    }

    try {
        $token = $client->getAccessToken($_GET['code']);
    } catch (\Exception $e) {
        exit($e->getMessage());
    }

    $_SESSION['openid'] = $token['openid'];
}

/**
 * 调用发送红包接口
 */
$cash = new Cash(APPID, MCHID, MCHKEY);

// 现金红包必需设置证书
$cash->setSSLCert(SSL_CERT, SSL_KEY);

// 设置红包信息
$cash->set('send_name',     '红包发送者名称');
$cash->set('act_name',      '活动名称');
$cash->set('remark',        '备注信息');
$cash->set('wishing',       '红包祝福语');
$cash->set('re_openid',     $_SESSION['openid']);
$cash->set('total_amount',  100);
$cash->set('total_num',     1);
$cash->set('mch_billno',    date('YmdHis').mt_rand(10000, 99999));

try {
    $response = $cash->getResponse();
} catch (\Exception $e) {
    exit($e->getMessage());
}

// var_dump($response);

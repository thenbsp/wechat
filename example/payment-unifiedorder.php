<?php

require './example.php';

use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\OAuth\Exception\AccessTokenException;

/**
 * 获取用户 openid
 */
// if( !isset($_SESSION['openid']) ) {

//     $client = new Client(APPID, APPSECRET);

//     if( !isset($_GET['code']) ) {
//         header('Location: '.$client->getAuthorizeUrl());
//     }

//     try {
//         $token = $client->getAccessToken($_GET['code']);
//     } catch (AccessTokenException $e) {
//         exit($e->getMessage());
//     }

//     $_SESSION['openid'] = $token['openid'];
// }

$unifiedorder = new Unifiedorder(APPID, MCHID, MCHKEY);
$unifiedorder->set('body',          'iphone 6 plus');
$unifiedorder->set('total_fee',     1);
$unifiedorder->set('openid',        'oWY-5jjLjo7pYUK86JPpwvcnF2Js'); // oWY-5jjLjo7pYUK86JPpwvcnF2Js
$unifiedorder->set('out_trade_no',  date('YmdHis').mt_rand(10000, 99999));
$unifiedorder->set('notify_url',    'http://dev.funxdata.com/wechat/example/payment-unifiedorder.php');

try {
    $response = $unifiedorder->getResponse();
} catch (\Exception $e) {
    exit($e->getMessage());
}

echo '<pre>';
print_r($response);
echo '</pre>';

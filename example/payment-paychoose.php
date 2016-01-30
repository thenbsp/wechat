<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\OAuth\Exception\AccessTokenException;
use Thenbsp\Wechat\Wechat\Jsapi;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Payment\Jsapi\PayChoose;

/**
 * 只能在微信中打开
 */
// if ( Util::isWechat() ) {
//     exit('请在微信中打开');
// }

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

/**
 * 生成 Jsapi 配置文件
 */
$jsapi = new Jsapi($accessToken);
$jsapi->setCache($cache);
$jsapi
    ->addApi('chooseWXPay')
    ->enableDebug();

/**
 * 统一下单获取 prepay_id
 */
$unifiedorder = new Unifiedorder(APPID, MCHID, MCHKEY);
$unifiedorder->set('body',          'iphone 6 plus');
$unifiedorder->set('total_fee',     1);
$unifiedorder->set('openid',        'oWY-5jjLjo7pYUK86JPpwvcnF2Js'); // oWY-5jjLjo7pYUK86JPpwvcnF2Js
$unifiedorder->set('out_trade_no',  date('YmdHis').mt_rand(10000, 99999));
$unifiedorder->set('notify_url',    'http://dev.funxdata.com/wechat/example/payment-unifiedorder.php');

/**
 * 生成 ChooseWXPay 配置文件
 */
$config = new PayChoose($unifiedorder);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wechat SDK</title>
</head>
<body ontouchstart="">

<h1>微信支付测试&nbsp;&nbsp;<a href="javascript:;" onclick="window.location.reload()">刷新</a></h1>

<h4>chooseWXPay 方式：</h4>
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">
支付 ￥<?php echo ($unifiedorder['total_fee'] / 100); ?> 元</button>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
/**
 * 注入 Jsapi 配置
 */
wx.config(<?php echo $jsapi; ?>);

/**
 * 点击支付按款
 */
var WXPayment = function() {

    var config = <?php echo $config; ?>;

    config.success = function() {
        alert('支付成功！');
    }

    config.cancel = function() {
        alert('用户取消成功！');
    }

    wx.chooseWXPay(config);
}
</script>
</body>
</html>
<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Payment\Jsapi\PayRequest;

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
 * 统一下单获取 prepay_id
 */
$unifiedorder = new Unifiedorder(APPID, MCHID, MCHKEY);
$unifiedorder->set('body',          '微信支付测试商品');
$unifiedorder->set('total_fee',     1);
$unifiedorder->set('openid',        $_SESSION['openid']); // oWY-5jjLjo7pYUK86JPpwvcnF2Js
$unifiedorder->set('out_trade_no',  date('YmdHis').mt_rand(10000, 99999));
$unifiedorder->set('notify_url',    EXAMPLE_URL.'payment-notify.php');

/**
 * 生成 PayRequest 配置文件
 */
$config = new PayRequest($unifiedorder);

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

<h4>WeixinJSBridge invoke 方式：</h4>
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">
支付 ￥<?php echo ($unifiedorder['total_fee'] / 100); ?> 元</button>

<script>
var WXPayment = function() {
    if( typeof WeixinJSBridge === 'undefined' ) {
        alert('请在微信在打开页面！');
        return false;
    }
    WeixinJSBridge.invoke('getBrandWCPayRequest', <?php echo $config; ?>, function(res) {
        switch(res.err_msg) {
            case 'get_brand_wcpay_request:cancel':
                alert('用户取消支付！');
                break;
            case 'get_brand_wcpay_request:fail':
                alert('支付失败！（'+res.err_desc+'）');
                break;
            case 'get_brand_wcpay_request:ok':
                alert('支付成功！');
                break;
            default:
                alert(JSON.stringify(res));
                break;
        }
    });
}
</script>
</body>
</html>
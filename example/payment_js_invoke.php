<?php

/**
 * 此示例为 微信支付（H5 invoke 方式）
 * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_7
 */

require './config.php';

use Thenbsp\Wechat\OAuth;
use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Payment\Unifiedorder;

// 一、以 JSAPI 方式的付款需要获取用户 Openid
if( !isset($_SESSION['openid']) ) {
    $o = new OAuth(APPID, APPSECRET);
    if( !isset($_GET['code']) ) {
        $o->authorize(Util::currentUrl());
    } else {
        $token = $o->getToken($_GET['code']);
        $_SESSION['openid'] = $token->openid;
    }
}

// 二、配置订单信息（参考：https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1）
$bag = new Bag();
$bag->set('appid', APPID);
$bag->set('mch_id', MCHID);
$bag->set('notify_url', NOTIFY_URL);
$bag->set('body', 'iphone 6 plus');
$bag->set('out_trade_no', date('YmdHis').mt_rand(10000, 99999));
$bag->set('total_fee', 1);
$bag->set('openid', $_SESSION['openid']);

/**
 * 三、统一下单
 */
$unifiedorder = new Unifiedorder($bag, MCHKEY);

/**
 * 四、生成支付配置文件
 */
$configJSON = Config::getPaymentConfig($unifiedorder, $asArray = false);

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
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">支付 ￥<?php echo ($bag->get('total_fee') / 100); ?> 元</button>

<script>
var WXPayment = function() {
    if( typeof WeixinJSBridge === 'undefined' ) {
        alert('请在微信在打开页面！');
        return false;
    }
    WeixinJSBridge.invoke('getBrandWCPayRequest', <?php echo $configJSON; ?>, function(res) {
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
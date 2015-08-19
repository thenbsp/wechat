<?php

/**
 * 此示例为 微信支付（H5 invoke 方式）
 * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_7
 */

require './config.php';

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\OAuth;
use Thenbsp\Wechat\Payment\Js;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Exception\PaymentException;

/**
 * 一、以 JSAPI 方式的付款需要获取用户 Openid
 */
if( !isset($_SESSION['openid']) ) {
    $o = new OAuth(APPID, APPSECRET);
    if( !isset($_GET['code']) ) {
        $o->authorize(Util::currentUrl());
    } else {
        $token = $o->getToken($_GET['code']);
        $_SESSION['openid'] = $token->openid;
    }
}

// 二、配置公众号（商户）信息
// $optionsResolver 在实际应用中一般以配置文件的形式存在，因此有必要将这些文件单独分离出来
// 不必在每次下单时去获取这些参数，并且该方法可满足在多个商户之间切换的需求：
$optionsResolver = array(
    'appid' => APPID,
    'mch_id' => MCHID,
    'mch_key' => MCHKEY,
    'notify_url' => 'http://example.com/payment_notify_1.php'
);

// 三、配置商品信息
// 配置商品基本参数，这里如果出现和 $optionsResolver 相同的参数前者将会被覆盖，这种方式在有些情况下很有用
// 比如有一款特殊的商口需要通知另一个特定接口时，比如示列中的 notify_url 项，
// 最终请求 unifiedorder 接口会提交 payment_notify.php 而不是 payment_notify_1.php：
$unifiedorder = new Unifiedorder($optionsResolver);
$unifiedorder->body('iphone 6 plus');
$unifiedorder->out_trade_no(date('YmdHis').mt_rand(10000, 99999));
$unifiedorder->total_fee('1'); // 单位为 “分”
$unifiedorder->openid($_SESSION['openid']);

/**
 * 四、生成支付配置文件
 */
$o = new Js($unifiedorder);

$configJSON = $o->getConfig();

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
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">支付 ￥<?php echo ($unifiedorder->getParams('total_fee') / 100); ?> 元</button>

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
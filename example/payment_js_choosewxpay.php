<?php

/**
 * 此示例为 微信支付（H5 chooseWXPay 方式）
 * http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E5.8F.91.E8.B5.B7.E4.B8.80.E4.B8.AA.E5.BE.AE.E4.BF.A1.E6.94.AF.E4.BB.98.E8.AF.B7.E6.B1.82
 */

require './config.php';

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\OAuth;
use Thenbsp\Wechat\Jssdk;
use Thenbsp\Wechat\Wechat;
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

$configJSON = $o->getConfigJssdk();

/**
 * 五：获取 JSSDK 配置
 */
$o = new Jssdk(APPID, APPSECRET);

$jssdkConfig = $o->getConfig(array('chooseWXPay'), true);

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

<h4>Jssdk chooseWXPay 方式：</h4>
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">支付 ￥<?php echo ($unifiedorder->getParams('total_fee') / 100); ?> 元</button>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
/**
 * 注入 JSSDK 配置
 */
wx.config(<?php echo $jssdkConfig; ?>);

/**
 * 点击支付按款
 */
var WXPayment = function() {

    var config = <?php echo $configJSON; ?>;

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
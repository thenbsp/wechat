<?php

/**
 * 此示例为 微信支付（H5 chooseWXPay 方式）
 * http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E5.8F.91.E8.B5.B7.E4.B8.80.E4.B8.AA.E5.BE.AE.E4.BF.A1.E6.94.AF.E4.BB.98.E8.AF.B7.E6.B1.82
 */

require './config.php';

use Thenbsp\Wechat\OAuth;
use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Payment\Unifiedorder;

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

/**
 * 二、生成 JSSDK 配置文件
 */
$wechat = new Wechat(APPID, APPSECRET);

$apis = array('onMenuShareTimeline', 'onMenuShareAppMessage');

$jssdkConfigJSON = Config::getJssdk($wechat, $apis, $debug = true, $asArray = false);


/**
 * 三、配置订单信息（参考：https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1）
 */
$bag = new Bag();
$bag->set('appid', APPID);
$bag->set('mch_id', MCHID);
$bag->set('notify_url', NOTIFY_URL);
$bag->set('body', 'iphone 6 plus');
$bag->set('out_trade_no', date('YmdHis').mt_rand(10000, 99999));
$bag->set('total_fee', 1);
$bag->set('openid', $_SESSION['openid']);

/**
 * 四、统一下单
 */
$unifiedorder = new Unifiedorder($bag, MCHKEY);

/**
 * 五、生成支付配置文件
 */
$configJSON = Config::getPaymentJssdkConfig($unifiedorder, $asArray = false);

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
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">支付 ￥<?php echo ($bag->get('total_fee') / 100); ?> 元</button>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
/**
 * 注入 JSSDK 配置
 */
wx.config(<?php echo $jssdkConfigJSON; ?>);

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
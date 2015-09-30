<?php

require './example.php';

use Thenbsp\Wechat\Jssdk;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\Payment\JsChooseWXPay;

session_start();

/**
 * 只能在微信中打开
 */
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
    exit('请在微信中打开');
}

/**
 * chooseWXPay 方式支付需要获取用户 openid
 */
if( !isset($_SESSION['openid']) ) {

    $client = new Client($wechat);

    if( !isset($_GET['code']) ) {
        $callback = EXAMPLE_URL.'payment-choosewxpay.php';
        header('Location: '.$client->getAuthorizeUrl($callback));
    } else {
        $token = $client->getAccessToken($_GET['code']);
        $_SESSION['openid'] = $token['openid'];
    }

    var_dump($_SESSION);
}

/**
 * 生成 JSSDK 配置文件
 */
$jssdk = new Jssdk($accessToken);
$jssdk
    ->addApi('onMenuShareTimeline')
    ->enableDebug();

$jssdkConfigJSON = $jssdk->getConfig();

/**
 * 生成支付配置文件
 */
$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => EXAMPLE_URL.'payment-notify.php',
    'openid'        => $_SESSION['openid']
);

$chooseWXPay = new JsChooseWXPay($wechat, $options);

$paymentConfigJSON = $chooseWXPay->getConfig();

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
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">支付 ￥<?php echo ($options['total_fee'] / 100); ?> 元</button>

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

    var config = <?php echo $paymentConfigJSON; ?>;

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
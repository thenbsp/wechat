<?php

require './example.php';

use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\Payment\JsBrandWCPayRequest;

session_start();

/**
 * 只能在微信中打开
 */
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
    exit('请在微信中打开');
}

/**
 * getBrandWCPayRequest 方式支付需要获取用户 openid
 */
if( !isset($_SESSION['openid']) ) {

    $client = new Client($wechat);

    if( !isset($_GET['code']) ) {
        $callback = EXAMPLE_URL.'payment-brandwcpayrequest.php';
        header('Location: '.$client->getAuthorizeUrl($callback));
    } else {
        $token = $client->getAccessToken($_GET['code']);
        $_SESSION['openid'] = $token['openid'];
    }
}

/**
 * 获取 getBrandWCPayRequest 配置文件
 */
$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => EXAMPLE_URL.'payment-notify.php',
    'openid'        => $_SESSION['openid']
);

$o = new JsBrandWCPayRequest($wechat, $options);

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

<h4>getBrandWCPayRequest 方式：</h4>
<button type="button" onclick="WXPayment()" style="font-size:16px;height:38px;">支付 ￥<?php echo ($options['total_fee'] / 100); ?> 元</button>

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
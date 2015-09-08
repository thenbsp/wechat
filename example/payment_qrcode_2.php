<?php

/**
 * 微信支付 - 扫码支付模式二
 * https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_5
 */
require './config.php';

use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Payment\Unifiedorder;

/**
 * 配置订单信息
 */
$bag = new Bag();
$bag->set('appid', APPID);
$bag->set('mch_id', MCHID);
$bag->set('notify_url', NOTIFY_URL);
$bag->set('body', 'iphone 6 plus');
$bag->set('out_trade_no', date('YmdHis').mt_rand(10000, 99999));
$bag->set('total_fee', 1); // 单位为 “分”
$bag->set('trade_type', 'NATIVE'); // NATIVE 时不需要 Openid

/**
 * 统一下单
 */
$unifiedorder = new Unifiedorder($bag, MCHKEY);

/**
 * 获取支付 URL（模式 2）
 */
$payurl = Config::getTemporaryPayurl($unifiedorder);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wechat SDK</title>
</head>
<body ontouchstart="">

<h1>扫描支付模式二</h1>
<p>请在 PC 端扫描二给码，如果在手机上可长按识别二维码</p>

<img src="https://chart.googleapis.com/chart?cht=qr&chs=220x220&choe=UTF-8&chld=L|2&chl=<?php echo $payurl; ?>" style="border:1px solid #ccc;" />

</script>
</body>
</html>
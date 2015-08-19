<?php

/**
 * 微信支付 - 扫码支付模式二
 * https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_5
 */
require './config.php';

use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Exception\PaymentException;

/**
 * 配置订单信息
 */
$unifiedorder = new Unifiedorder;
$unifiedorder->appid(APPID);
$unifiedorder->mch_id(MCHID);
$unifiedorder->mch_key(MCHKEY);
$unifiedorder->body('iphone 6 plus');
$unifiedorder->out_trade_no(date('YmdHis').mt_rand(10000, 99999));
$unifiedorder->total_fee('1'); // 单位为 “分”
$unifiedorder->trade_type('NATIVE'); // NATIVE 时不需要 Openid
$unifiedorder->notify_url('http://example.com/payment_notify_1.php');

/**
 * 统一下（trade_type 为 NATIVE 时，$response 将包含 code_url 字段，只需将该字段生成二维码即可）
 */
try {
    $response = $unifiedorder->getResponse();
} catch (PaymentException $e) {
    exit($e->getMessage());
}

$payurl = urlencode($response['code_url']);

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

<img src="https://chart.googleapis.com/chart?cht=qr&chs=220x220&choe=UTF-8&chld=L|2&chl=<?php echo $payurl; ?>" style="border:1px solid #ccc;" />

</script>
</body>
</html>
<?php

require './_example.php';

/**
 * 微信扫码支付-模式二（临时模式）
 */
use Thenbsp\Wechat\Payment\QrcodeTemporary;

$options = array(
    'body' => 'iphone 6 plus',
    'total_fee' => 1,
    'out_trade_no' => date('YmdHis').mt_rand(10000, 99999),
    'notify_url' => EXAMPLE_URL.'_example/payment-notify.php',
    'trade_type' => 'NATIVE'
);

$qrcode = new QrcodeTemporary($wechat, $options);

// 获取支付二维码
$payurl = $qrcode->getPayurl();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wechat SDK</title>
</head>
<body ontouchstart="">

<h1>微信扫码支付-模式二</h1>
<p>请在 PC 端扫描二给码，如果在手机上可长按识别二维码</p>

<img src="http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&text=<?php echo $payurl; ?>" style="border:1px solid #ccc;" />

</script>
</body>
</html>
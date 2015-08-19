<?php

/**
 * 此示例为 微信支付（扫码支付 模式一）
 * https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_4
 */

require './config.php';

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\SignGenerator;

use Thenbsp\Wechat\Payment\Qrcode;

$qrcode = new Qrcode(array(
    'appid' => APPID,
    'mch_id' => MCHID,
    'mch_key' => MCHKEY,
    'product_id' => '123456789'
));

$payurl = $qrcode->getPayURL();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wechat SDK</title>
</head>
<body ontouchstart="">

<h1>扫描支付模式一</h1>

<img src="https://chart.googleapis.com/chart?cht=qr&chs=220x220&choe=UTF-8&chld=L|2&chl=<?php echo $payurl; ?>" style="border:1px solid #ccc;" />

</script>
</body>
</html>
<?php

require './example.php';

use Thenbsp\Wechat\Payment\Qrcode\Forever;

// 产品（或订单）ID
$productId = date('YmdHis').mt_rand(10000, 99999);

// 获取支付链接
$qrcode = new Forever(APPID, MCHID, MCHKEY);
$payurl = $qrcode->getPayurl($productId);

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
<p>请在 PC 端扫描二给码，如果在手机上可长按识别二维码</p>

<img src="http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&text=<?php echo $payurl; ?>" style="border:1px solid #ccc;" />

</script>
</body>
</html>

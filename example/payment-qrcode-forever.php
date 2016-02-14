<?php

require './example.php';

use Thenbsp\Wechat\Payment\Qrcode\Forever;

/**
 * 生成二维码
 */
$qrcode = new Forever(APPID, MCHID, MCHKEY);
$qrcode->set('product_id', date('YmdHis').mt_rand(10000, 99999));

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

<img src="http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&text=<?php echo $qrcode; ?>" style="border:1px solid #ccc;" />

</script>
</body>
</html>

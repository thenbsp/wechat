<?php

require './_example.php';

/**
 * 微信扫码支付-模式一（永久模式）
 * 
 * 原理说明：
 * 永久模式只需传入一个 product_id，该 product_id 可随意指定一个唯一值即可
 * 当用户在扫码时，微信将根据 product_id 从 callback 地址（微信后台设置）调用商品信息
 * 因此 product_id 可以理解为一个订单（或产品）的唯一 ID
 */
use Thenbsp\Wechat\Payment\QrcodeForever;

// 选项 $options 默认只需传入 product_id 即可，其它选项（比如 appid, mch_id, nonce_str, time_stamp）
// 将自动生成，如果需要指定这些参数，可在 $options 数组中定义
$options = array(
    'product_id' => date('YmdHis').mt_rand(10000, 99999)
);

// QrcodeForever 模式需要两个参数，对象 $wecaht（全局） 和 订单选项 $options
$qrcode = new QrcodeForever($wechat, $options);

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

<h1>扫描支付模式一</h1>
<p>请在 PC 端扫描二给码，如果在手机上可长按识别二维码</p>

<img src="http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&text=<?php echo $payurl; ?>" style="border:1px solid #ccc;" />

</script>
</body>
</html>
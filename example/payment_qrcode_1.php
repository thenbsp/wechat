<?php

/**
 * 此示例为 微信支付（扫码支付 模式一）
 * https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_4
 */

require './config.php';

use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Util\Bag;

/**
 * 配置订单参数
 */
$bag = new Bag();
$bag->set('appid', APPID);
$bag->set('mch_id', MCHID);
$bag->set('product_id', date('YmdHis').mt_rand(10000, 99999));

/**
 * 获取支付 URL（模式 1）
 */
$payurl = Config::getForeverPayurl($bag, MCHKEY);

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
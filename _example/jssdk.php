<?php

require './_example.php';

use Thenbsp\Wechat\Jssdk;

/**
 * 只能在微信中打开
 */
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
    exit('请在微信中打开');
}

/**
 * 生成 JSSDK 配置文件
 */
$jssdk = new Jssdk($accessToken);
$jssdk
    ->addApi('onMenuShareTimeline')
    ->addApi('onMenuShareAppMessage')
    ->enableDebug();

$configJSON = $jssdk->getConfig();

// 返回 Array
// $configArray = $jssdk->getConfig(true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wechat SDK</title>
</head>
<body ontouchstart="">

如果弹出 {"errMsg": "config:ok"} 说明配置成功！

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config(<?php echo $configJSON; ?>);
</script>
</body>
</html>

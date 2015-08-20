<?php

require './config.php';

use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Wechat;

/**
 * 只能在微信中打开
 */
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
    exit('请在微信中打开');
}

/**
 * 生成 JSSDK 配置文件
 */
$wechat = new Wechat(APPID, APPSECRET);

$apis = array('onMenuShareTimeline', 'onMenuShareAppMessage');

$configJSON = Config::getJssdk($wechat, $apis, $debug = true, $asArray = false);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>JSSDK DEMO</title>
</head>
<body ontouchstart="">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config(<?php echo $configJSON; ?>);
</script>
</body>
</html>
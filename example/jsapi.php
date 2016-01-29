<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Wechat\Jsapi;
use Thenbsp\Wechat\Wechat\Jsapi\Ticket;

/**
 * 只能在微信中打开
 */
if ( Util::isWechat() ) {
    exit('请在微信中打开');
}

// 提示：使用前必需登录微信公众平台->公众号设置->功能设置->JS接口安全域名 里设置当前域名

/**
 * 第一步：定义 Jsapi Ticket 对象
 */
$ticket = new Ticket($accessToken);
// 推荐使用缓存（可选）
$ticket->setCacheBridge($cache);

/**
 * 第二步：生成 Jsapi 配置文件
 */
$jsapi = new Jsapi($ticket);
// 注入接口
$jsapi
    ->addApi('onMenuShareTimeline')
    ->addApi('onMenuShareAppMessage');
// 调试模式
$jsapi->enableDebug();

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
wx.config(<?php echo $jsapi; ?>);
</script>
</body>
</html>

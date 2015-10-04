# JSSDK 配置

JSSDK 用来生成 JSAPI 配置文件，详情 http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html

定义 Jssdk 需要注入 AccessToken 对象

```php
use Thenbsp\Wechat\Jssdk;

$jssdk = new Jssdk($accessToken);
```

定义接口

```php
$jssdk
    ->addApi('onMenuShareAppMessage')
    ->addApi('onMenuShareTimeline')
```

也可以使用数组式

```php
$apis = array('onMenuShareAppMessage', 'onMenuShareTimeline')
$jssdk->addApi($apis);
```

开启调试模式

```php
$jssdk->enableDebug();
```

获取配置

```php
$config = $jssdk->getConfig();
```

结果

```php
{
    "appId":        "wx345f3830c2897po4",
    "nonceStr":     "5610f512b0aew4",
    "timestamp":    "1443951658",
    "signature":    "d417c5489b69fba8c118157e87da80818b974a46",
    "jsApiList":[
        "onMenuShareTimeline",
        "onMenuShareAppMessage"
    ],
    "debug": true
}
```

也可以生成数组

```php
$configArray = $jssdk->getConfig(true);
```

将配置文件注入到接口

```html
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config(<?php echo $config; ?>);
</script>
```

如果是数组，可以单独指定参数

```html
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
    "appId":        "<?php echo $configArray['appId']; ?>",
    "nonceStr":     "<?php echo $configArray['nonceStr']; ?>",
    "timestamp":    "<?php echo $configArray['timestamp']; ?>",
    "signature":    "<?php echo $configArray['signature']; ?>",
    "jsApiList":    ["onMenuShareTimeline", "onMenuShareAppMessage" ],
    "debug":        true
});
</script>
```
# 微信支付 - JSAPI chooseWXPay

chooseWXPay 接口依赖于 JSSDK，因此需要先注入 JSSDK 配置：

```php
use Thenbsp\Wechat\Jssdk;

/**
 * 生成 JSSDK 配置文件
 */
$jssdk = new Jssdk($accessToken);
$jssdk->addApi('chooseWXPay');

$jssdkConfigJSON = $jssdk->getConfig();
```

PHP：

```php
use Thenbsp\Wechat\Payment\JsChooseWXPay;

$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => 'Your notify url',
    'openid'        => $_SESSION['openid']
);

$o = new JsChooseWXPay($wechat, $options);

$paymentConfigJSON = $o->getConfig();
```

Javascript：

```javascript
// 注入 JSSDK 配置
wx.config(<?php echo $jssdkConfigJSON; ?>);

// 点击支付按款
var WXPayment = function() {

    var config = <?php echo $paymentConfigJSON; ?>;

    config.success = function() {
        alert('支付成功！');
    }

    config.cancel = function() {
        alert('用户取消成功！');
    }

    wx.chooseWXPay(config);
}
```

HTML：

```html
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<button type="button" onclick="WXPayment()">支付 ￥<?php echo ($options['total_fee'] / 100); ?> 元</button>

```
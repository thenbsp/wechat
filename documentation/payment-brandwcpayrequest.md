# 微信支付 - JSAPI BrandWCPayRequest

PHP：

```php
use Thenbsp\Wechat\Payment\JsBrandWCPayRequest;

$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => 'Your notify url',
    'openid'        => $_SESSION['openid']
);

$o = new JsBrandWCPayRequest($wechat, $options);

$configJSON = $o->getConfig();
```

Javascript：

```javascript
<script>
var WXPayment = function() {
    if( typeof WeixinJSBridge === 'undefined' ) {
        alert('请在微信在打开页面！');
        return false;
    }
    WeixinJSBridge.invoke('getBrandWCPayRequest', <?php echo $configJSON; ?>, function(res) {
        switch(res.err_msg) {
            case 'get_brand_wcpay_request:cancel':
                alert('用户取消支付！');
                break;
            case 'get_brand_wcpay_request:fail':
                alert('支付失败！（'+res.err_desc+'）');
                break;
            case 'get_brand_wcpay_request:ok':
                alert('支付成功！');
                break;
            default:
                alert(JSON.stringify(res));
                break;
        }
    });
}
</script>
```

HTML：

```html
<button type="button" onclick="WXPayment()">支付 ￥<?php echo ($options['total_fee'] / 100); ?> 元</button>
```
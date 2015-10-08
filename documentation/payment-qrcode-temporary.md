# 微信支付 - 扫码支付 模式二

PHP：

```php
use Thenbsp\Wechat\Payment\QrcodeTemporary;

$options = array(
    'body' => 'iphone 6 plus',
    'total_fee' => 1,
    'out_trade_no' => date('YmdHis').mt_rand(10000, 99999),
    'notify_url' => EXAMPLE_URL.'payment-notify.php',
    'trade_type' => 'NATIVE'
);

$qrcode = new QrcodeTemporary($wechat, $options);

$payurl = $qrcode->getPayurl();
```

HTML:

```html
<img src="http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&text=<?php echo $payurl; ?>" />
```
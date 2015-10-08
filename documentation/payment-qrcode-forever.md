# 微信支付 - 扫码支付 模式一

模式一即为 **永久模式**

永久模式只需传入一个 product_id，该 product_id 可随意指定一个唯一值即可
当用户在扫码时，微信将根据 product_id 从 callback 地址（微信后台设置）调用商品信息
因此 product_id 可以理解为一个订单（或产品）的唯一 ID！

PHP:

getPayurl() 方法返回支付地址，只需将 $payurl 生成二维码即可！

```php
use Thenbsp\Wechat\Payment\QrcodeForever;

$options = array(
    'product_id' => date('YmdHis').mt_rand(10000, 99999)
);

$qrcode = new QrcodeForever($wechat, $options);

$payurl = $qrcode->getPayurl();
```

生成二维码：

示例中为在线生成二维码，实际项目中可使用二维码生成库！

```html
<img src="http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&text=<?php echo $payurl; ?>" />
```
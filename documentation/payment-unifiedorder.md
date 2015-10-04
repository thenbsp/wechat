# 微信支付 - 统一下单

配置订单参数

```php
$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'openid'         => 'user openid',
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => EXAMPLE_URL.'payment-notify.php'
);
```

统一下单需要注入 Wechat 对象，getResponse 方法在发生错误发抛出 \Exception 异常

```php
$unifiedorder = new Unifiedorder($wechat, $options);

try {
    $response = $unifiedorder->getResponse();
} catch (\Exception $e) {
    exit($e->getMessage());
}

var_dump($response);
```
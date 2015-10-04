# 微信支付 - 统一下单

配置订单参数，trade_type 默认为 JSAPI，因此需要 openid，获取 openid 可使用 OAuth 网页授权

```php
$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'openid'        => 'user openid',
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => 'Your notify url'
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

统一下单响应结果

```php
Array
(
    [return_code]   => SUCCESS
    [return_msg]    => OK
    [appid]         => wx345f3830c2897po4
    [mch_id]        => 1241642124
    [nonce_str]     => hcenddZlBKOHzulZ
    [sign]          => 8CC689EB26D1234ACE219795C64BEE81
    [result_code]   => SUCCESS
    [prepay_id]     => wx201510041901594ea5e896a00156575586
    [trade_type]    => JSAPI
)
```
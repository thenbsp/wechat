# 微信支付 - 扫码支付 响应订单（模式二）

扫码支付（模式二）中，当用户扫码之后，微信服务器将携带 **订单基本参数** 请求 Callback 地址（Callback 地址请在公众号后台 -> 微信支付 -> 开发配置中设置），开发者需要根据参数中的 product_id 在数据库中查找并返回该订单信息。

本次请求中，将携带以下参数：

- appid
- openid
- mch_id
- is_subscribe
- nonce_str
- product_id
- sign

请求 && 响应对象

```php
use Thenbsp\Wechat\Payment\QrcodeRequest;
use Thenbsp\Wechat\Payment\QrcodeResponse;

$request    = new QrcodeRequest();
$response   = new QrcodeResponse('Wechat Object', 'Order Options');

```

接收到请求后，需要先判断本次请深求有否有效，因为是服务器对服务器之间的请求，调试请使用日志。

```php
if( !$request->isValid() ) {
    QrcodeResponse::fail('Invalid Request');
}
```

根据 Request 中的 product_id 去数据库中查找并返回订单信息。

```php

$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => 'Your notify url',
    'trade_type'    => 'NATIVE'
);

$response = new QrcodeResponse($wechat, $options);
$response->send();
```

返回错误消息

当查找过程中，如果发生异常需要提示用户，可以使用 fail 方法返回错误消息，比如订单没有被找到：

```php
QrcodeResponse::fail('对不起，没有找到该订单:'. $request['product_id']);
```
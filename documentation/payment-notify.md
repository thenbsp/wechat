# 微信支付 - 支付通知

统一下单时有一个必填参数：notify_url，即支付成功时的通知地址用，以处理后期业务，比如修改订单状态，通知请求中将会携带以下参数：

- appid         商户所对应的公众号 APPID
- bank_type     用户支付所使用的银行类型
- cash_fee      用户最终实付款
- fee_type      用户支付的货币类型 CNY 为人民币
- is_subscribe  用户是否关注了公众号
- mch_id        商户 ID
- nonce_str     随机字符
- openid        用户相对于公众号的唯一ID （同一个用户在不同公众号中 Openid 不同）
- out_trade_no  为订单 ID，和统一下单时的 out_trade_no 对应。
- result_code   交易是否成功 SUCCESS/FAIL
- return_code   通信状态标识 SUCCESS/FAIL
- sign          数据签名
- time_end      交易结束时间
- total_fee     订单的总金额（和 cash_fee 不一样）
- trade_type    支付方式类别（JSAPI：公众号支付，NATIVE：原生扫码支付, APP：APP 支付, WAP：手机浏览器支付, MICROPAY：刷卡支付）
- transaction_id 微信交易 ID

## 检测本次请求是否有效

通知请求为服务端对服务器端，因些调试请使用日志。

```php
use Thenbsp\Wechat\Payment\NotifyRequest;

$request = new NotifyRequest();

if ( !$request->isValid() ) {
    exit('Invalid Request');
}
```

## 查看请求参数

```php

// 查看本次请求内容
var_dump($request->getContent());

// 查看全部参数
var_dump($request->getOptions());

// 查看指定参数
var_dump($request['openid']);
var_dump($request['out_trade_no']);
var_dump($request['cash_fee']);

// 获取到这些参数后，可进行后期业务处理，比如修改订单状态为 “已付款”，示例：
// UPDATE order SET status = "SUCCESS" WHERE orderid = $request['out_trade_no']

```
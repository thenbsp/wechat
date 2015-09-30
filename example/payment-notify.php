<?php

require './example.php';

/**
 * 微信支付成功通知
 *
 * 1，统一下单时有一个必填参数：notify_url，即支付成功时的通知地址，用以处理后期业务，如果修改订单状态。
 * 2，当用户支付成功时，微信服务器会请求 notify_url 地址并携带订单信息。
 * 3，本次请求中携带的订单信息包含以下参数：
 *
 *      'appid', 'bank_type', 'cash_fee', 'fee_type', 'is_subscribe', 'mch_id',
 *      'nonce_str', 'openid', 'out_trade_no', 'result_code', 'return_code',
 *      'sign', 'time_end', 'total_fee', 'trade_type', 'transaction_id'
 *      
 *      - appid         商户所对应的公众号 APPID
 *      - bank_type     用户支付所使用的银行类型
 *      - cash_fee      用户最终实付款
 *      - fee_type      用户支付的货币类型 CNY 为人民币
 *      - is_subscribe  用户是否关注了公众号
 *      - mch_id        商户 ID
 *      - nonce_str     随机字符
 *      - openid        用户相对于公众号的唯一ID （同一个用户在不同公众号中 Openid 不同）
 *      - out_trade_no  为订单 ID，和统一下单时的 out_trade_no 对应。
 *      - result_code   交易是否成功 SUCCESS/FAIL
 *      - return_code   通信状态标识 SUCCESS/FAIL
 *      - sign          数据签名
 *      - time_end      交易结束时间
 *      - total_fee     订单的总金额（和 cash_fee 不一样）
 *      - trade_type    支付方式类别（JSAPI：公众号支付，NATIVE：原生扫码支付, APP：APP 支付, WAP：手机浏览器支付, MICROPAY：刷卡支付）
 *      - transaction_id 微信交易 ID
 *
 * 4，根据以上参数进行后期业务处理，比如修改订单状态，只需要获取 out_trade_no 去 UPDATE 既可。
 *
 */

/**
 * 服务器端对服务器端的请求，示例中使用日志（文件缓存）查看
 */

// Notify 请求对象
$request = new \Thenbsp\Wechat\Payment\Notify\Request();

// 验证本次请是否有效（只验证数据结构，不验证公众号 ID）
if (!$request->isValid()) {
    $cache->set('payment-notify-error', $request->getError());
    exit;
}

// 获取获部内容（数组）
$cache->set('payment-notify-options', $request->getOptions());

// 获取指定内容
$cache->set('payment-notify-orderid', $request['out_trade_no']);

// 获取原始内容
$cache->set('payment-notify-rawcontent', $request->getContent());

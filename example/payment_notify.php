<?php

require './config.php';

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\Cache;

if( !$request = file_get_contents('php://input') ) {
    exit('Invalid Request');
}

$request = Util::XML2Array($request);

$cache = new Cache('../Storage');
$cache->set('payment_notify', $request);

// 微信服务器会在支付成功后该求该 URL，以 XML 的方式提交此次支付信息，具体信息类似：
// {
//     "appid":"wx345f3830c28971f4",
//     "bank_type":"CFT",
//     "cash_fee":"1",
//     "fee_type":"CNY",
//     "is_subscribe":"Y",
//     "mch_id":"1241642202",
//     "nonce_str":"fTbkMTXgMmnvTaO1",
//     "openid":"oWY-5jjLjo7pYUK86JPpwvcnF2Js",
//     "out_trade_no":"124164220220150803161536",
//     "result_code":"SUCCESS",
//     "return_code":"SUCCESS",
//     "sign":"4B8948BD3E831394C956B5DFA8AAEB1E",
//     "time_end":"20150803161547",
//     "total_fee":"1",
//     "trade_type":"JSAPI",
//     "transaction_id":"1005130236201508030539058581"
// }

// 接到这些数据后，就可以实现自己的业务，比如将订单状态改为“已付款”
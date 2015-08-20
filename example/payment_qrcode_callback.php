<?php

require './config.php';

use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\SignGenerator;

use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Exception\PaymentException;

if( !$request = file_get_contents('php://input') ) {
    exit('Invalid Request');
}

/**
 * 微信服务器请求过来的数据
 */
$request = Util::XML2Array($request);
// $request = array(
//     "appid" => "wx345f3830c28971f4",
//     "openid" => "oWY-5jjLjo7pYUK86JPpwvcnF2Js",
//     "mch_id" => "1241642202",
//     "is_subscribe" => "Y",
//     "nonce_str" => "fhKb6Fkzm3UJrX95",
//     "product_id" => "2015080618052364076",
//     "sign" => "1ED0F1A052F5212009E7C5DB89C57789"
// );

/**
 * 配置订单信息（这一步在实际应用中就是根据 $request 中的 product_id 在应用中获取订单信息）
 */
$requestBag = new Bag();
$requestBag->set('appid', $request['appid']);
$requestBag->set('mch_id', $request['mch_id']);
$requestBag->set('notify_url', NOTIFY_URL);
$requestBag->set('body', 'iphone 6 plus');
$requestBag->set('out_trade_no', date('YmdHis').mt_rand(10000, 99999));
$requestBag->set('total_fee', 1); // 单位为 “分”
$requestBag->set('trade_type', 'NATIVE'); // NATIVE 时不需要 Openid

/**
 * 统一下单
 */
$unifiedorder = new Unifiedorder($requestBag, MCHKEY);

try {
    $response = $unifiedorder->getResponse();
} catch (PaymentException $e) {
    exit($e->getMessage());
}

/**
 * 响应订单参数包
 */
$responseBag = new Bag();
$responseBag->set('appid', $request['appid']);
$responseBag->set('mch_id', $request['mch_id']);
$responseBag->set('nonce_str', $request['nonce_str']);
$responseBag->set('prepay_id', $response['prepay_id']);
$responseBag->set('return_code', 'SUCCESS');
$responseBag->set('result_code', 'SUCCESS');
$responseBag->set('return_msg', 'return message');
$responseBag->set('err_code_des', 'err code description');


$signGenerator = new SignGenerator($responseBag);
$signGenerator->onSortAfter(function($bag) use ($unifiedorder) {
    $bag->set('key', $unifiedorder->getKey());
});

$responseBag->set('sign', $signGenerator->getResult());
$responseBag->remove('key');

$xml = Util::array2XML($responseBag->all());

echo $xml;

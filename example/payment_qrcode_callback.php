<?php

require './config.php';

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\Cache;
use Thenbsp\Wechat\Util\SignGenerator;

use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Exception\PaymentException;

if( !$request = @file_get_contents('php://input') ) {
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
 * 统一下单
 */
$unifiedorder = new Unifiedorder();
$unifiedorder->appid(APPID);
$unifiedorder->mch_id(MCHID);
$unifiedorder->mch_key(MCHKEY);
$unifiedorder->body('iphone 6 plus');
$unifiedorder->out_trade_no(date('YmdHis').mt_rand(10000, 99999));
$unifiedorder->total_fee('1');
$unifiedorder->openid($request['openid']);
$unifiedorder->notify_url('http://example.com/payment_notify_1.php');

try {
    $response = $unifiedorder->getResponse();
} catch (PaymentException $e) {
    exit($e->getMessage());
}

/**
 * 响应订单
 */
$params = array(
    'return_code' => 'SUCCESS',
    'result_code' => 'SUCCESS',
    'return_msg' => 'return message',
    'appid' => $request['appid'],
    'err_code_des' => 'err code description',
    'mch_id' => $request['mch_id'],
    'nonce_str' => $request['nonce_str'],
    'prepay_id' => $response['prepay_id']
);

$signGenerator = new SignGenerator($params);
$signGenerator->addParams('key', MCHKEY);

$params['sign'] = $signGenerator->getResult();

$xml = Util::array2XML($params);

$cache = new Cache('../Storage');
$cache->set('payment_qrcode_callback', $request);

echo $xml;

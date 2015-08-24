<?php

require './config.php';

use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Payment\QrcodeCallbackResponse;
use Thenbsp\Wechat\Exception\PaymentException;

/**
 * 一、处理请求参数
 */
if( !$request = file_get_contents('php://input') ) {
    exit('Invalid Request');
}

$request = Util::XML2Array($request);

// 请求过来的 product_id，根据 product_id 去数据库里查询商品，提交到第二步：
// $productId = $request->get('product_id');

/**
 * 二、统一下单
 */
$requestBag = new Bag();
$requestBag->set('appid',           APPID);
$requestBag->set('mch_id',          MCHID);
$requestBag->set('notify_url',      NOTIFY_URL);
$requestBag->set('body',            'iphone 6 plus');
$requestBag->set('out_trade_no',    date('YmdHis').mt_rand(10000, 99999));
$requestBag->set('total_fee',       1);
$requestBag->set('trade_type',      'NATIVE');

try {
    $unifiedorder   = new Unifiedorder($requestBag, MCHKEY);
    $response       = $unifiedorder->getResponse();
} catch (PaymentException $e) {
    exit($e->getMessage());
}

/**
 * 三、响应订单
 */
$responseBag = new Bag();
$responseBag->set('appid',          $request['appid']);
$responseBag->set('mch_id',         $request['mch_id']);
$responseBag->set('nonce_str',      $request['nonce_str']);
$responseBag->set('prepay_id',      $response['prepay_id']);
$responseBag->set('return_code',    'SUCCESS');
$responseBag->set('result_code',    'SUCCESS');

$qrcodeResponse = new QrcodeCallbackResponse($responseBag, MCHKEY);
$qrcodeResponse->send();

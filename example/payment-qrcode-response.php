<?php

require './example.php';

use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Payment\Qrcode\RequestContext;
use Thenbsp\Wechat\Payment\Qrcode\ResponseContext;

/**
 * 验证请求（正常情部下请求中包含 appid, openid, mch_id, is_subscribe, nonce_str, product_id, sign）
 */
try {
    $context = new RequestContext();
} catch (\InvalidArgumentException $e) {
    ResponseContext::fail($e->getMessage());
}

/**
 * 统一下单（根据第一步中的 product_id 去数据库查找订单信息并下单）
 * For example: SELECT * FROM product WHERE id = $context['product_id'];
 */
$unifiedorder = new Unifiedorder(APPID, MCHID, MCHKEY);
$unifiedorder->set('body',          'iphone 6 plus');
$unifiedorder->set('total_fee',     1);
$unifiedorder->set('out_trade_no',  date('YmdHis').mt_rand(10000, 99999));
$unifiedorder->set('notify_url',    'http://dev.funxdata.com/wechat/example/payment-unifiedorder.php');

/**
 * 响应订单
 */
ResponseContext::success($unifiedorder);

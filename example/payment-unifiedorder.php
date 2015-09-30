<?php

require './example.php';

use Thenbsp\Wechat\Payment\Unifiedorder;

$options = array(
    'body' => 'iphone 6 plus',
    'total_fee' => 1,
    'openid' => 'oWY-5jjLjo7pYUK86JPpwvcnF2Js',
    'out_trade_no' => date('YmdHis').mt_rand(10000, 99999),
    'notify_url' => EXAMPLE_URL.'payment-notify.php'
);

$unifiedorder = new Unifiedorder($wechat, $options);

try {
    $response = $unifiedorder->getResponse();
} catch (Exception $e) {
    exit($e->getMessage());
}

echo '<pre>';
print_r($response);
echo '</pre>';

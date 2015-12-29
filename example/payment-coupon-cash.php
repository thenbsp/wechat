<?php

require './example.php';

use Thenbsp\Wechat\Payment\Coupon\Cash;

$options = array(
    'send_name'     => '梵响互动',
    'act_name'      => '猜灯谜抢红包活动',
    'remark'        => '猜越多得越多，快来抢！',
    're_openid'     => 'ob4npwpYsDT6CQGHRDl9U50V6-RE',
    'total_amount'  => 100,
    'total_num'     => 1,
    'wishing'       => '感谢您参加猜灯谜活动，祝您元宵节快乐！',
    'mch_billno'    => date('YmdHis').mt_rand(10000, 99999)
);

try {
    $cash = new Cash($wechat, $options);
    $response = $cash->getResponse();
} catch (\InvalidArgumentException $e) {
    exit($e->getMessage());
} catch (\Exception $e) {
    exit($e->getMessage());
}

echo '<pre>';
print_r($response);
echo '</pre>';

// Array
// (
//     [return_code] => SUCCESS
//     [return_msg] => 发放成功
//     [result_code] => SUCCESS
//     [mch_billno] => 2015122915291444042
//     [mch_id] => 1283267801
//     [wxappid] => wxd8da84ed2a26aa06
//     [re_openid] => ob4npwpYsDT6CQGHRDl9U50V6-RE
//     [total_amount] => 100
//     [send_listid] => 0010801705201512290383960635
//     [send_time] => 20151229152915
// )

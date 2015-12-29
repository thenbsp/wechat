# 微信支付 - 现金红包

现金红包需要配置商户 mchid、mchkey 和 证书：

```php
use Thenbsp\Wechat\Wechat;

$options = array(
    'appid'     => 'app id',
    'appsecret' => 'app secret',
    'mchid'     => 'mch id',
    'mchkey'    => 'mch key',
    'authenticate_cert' => array(
        'cert'  => '/path/to/apiclient_cert.pem',
        'key'   => '/path/to/apiclient_key.pem'
    )
);

$wechat = new Wechat($options);
```

向指定用户发发送现金红包：

```php
use Thenbsp\Wechat\Payment\Coupon\Cash;

$options = array(
    'send_name'     => '公众号名称',
    'act_name'      => '活动名称',
    'remark'        => '备注',
    're_openid'     => '接收红包用户 Openid',
    'total_amount'  => 100, // 单位为分
    'total_num'     => 1,
    'wishing'       => '祝福语',
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
```

如果发送成功，将返回以下信息：

```php
Array
(
    [return_code] => SUCCESS
    [return_msg] => 发放成功
    [result_code] => SUCCESS
    [mch_billno] => 2015122915291444042
    [mch_id] => 1283267801
    [wxappid] => wxd8da84ed2a26aa06
    [re_openid] => ob4npwpYsDT6CQGHRDl9U50V6-RE
    [total_amount] => 100
    [send_listid] => 0010801705201512290383960635
    [send_time] => 20151229152915
)
```

详细示例：[/documentation/payment-coupon-cash.md](/documentation/payment-coupon-cash.md)
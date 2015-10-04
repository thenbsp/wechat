# Wechat 对象

Wechat 对象提供统一的微信公众号（服务器）管理功能。

```php
use Thenbsp\Wechat\Wechat;

$options = array(
    'appid'     => 'your appid',
    'appsecret' => 'your appsecret',
);

$wechat = new Wechat($options);
```

如果需要使用微信支付，必需设置 ``mchid`` 和 ``mchkey``，``mchid`` 和 ``mchkey`` 需要在商户后台获取

```php
use Thenbsp\Wechat\Wechat;

$options = array(
    'appid'     => 'your appid',
    'appsecret' => 'your appsecret',
    'mchid'     => 'your mch_id',
    'mchkey'    => 'your mch key'
);

$wechat = new Wechat($options);
```

如果需要使用退款接口，必需设置证书 ``authenticate_cert``，``authenticate_cert`` 为一个包含 ``cert`` 和 ``key`` 的数组，证书请在微信商户后台下载

```php
use Thenbsp\Wechat\Wechat;

$options = array(
    'appid'     => 'your appid',
    'appsecret' => 'your appsecret',
    'mchid'     => 'your mch_id',
    'mchkey'    => 'your mch key',
    'authenticate_cert' => array(
        'cert'  => '/path/to/apiclient_cert.pem',
        'key'   => '/path/to/apiclient_key.pem'
    )
);

$wechat = new Wechat($options);
``
# 微信 SDK

微信公众平台第三方 SDK 版，简单，优雅、健壮，遵循 psr4 自动加载标准！

在线演示（请使用微信扫描打开）：

![此处输入图片的描述][1]

详细示例请看 ``./_example`` 目录中的示例！

## 安装

```php
composer require thenbsp/wechat
```

## 配置公众号 && 商户

```php
use Thenbsp\Wechat\Wechat;

$options = array(
    'appid' => 'your appid',
    'appsecret' => 'your appsecret',
    'mchid' => 'your mch_id',
    'mchkey' => 'your mch key'
);

$wechat = new Wechat($options);
```

## 缓存对象

```php
use Thenbsp\Wechat\Util\Cache;

$cache = new Cache('./Storage'); // Storage 需可写权限
```

## AccessToken 对象

**注意：公众号 AccessToken 需要全局缓存维护，重复获取将导致旧 AccessToken 失效，因此将它存在 ``./Storage`` 目录下，需要设置该目录可写权限（出于安全考虑，请设置禁上浏览器直接访问该目录或将该目录移到处 Web 根目录以外）！**

```php
use Thenbsp\Wechat\AccessToken;

$accessToken = new AccessToken($wechat, $cache);

var_dump($accessToken->getAccessToken());
```

详细使用方式请参考 ``./_example/access_token.php`` 文件

## Ticket 对象

**注意：公众号 Ticket 需要全局缓存维护，重复获取将导致旧 Ticket 失效，因此将它存在 ``./Storage`` 目录下，需要设置该目录可写权限（出于安全考虑，请设置禁上浏览器直接访问该目录或将该目录移到处 Web 根目录以外）！**

Ticket对象依赖于 AccessToken 对象，因此需要注入 AccessToken，公众号 ticket 分为 ``jsapi`` 和 ``wx_card``，getTicket 方法可传入一个可选参数

```php
use Thenbsp\Wechat\Ticket;

$ticket = new Ticket($accessToken);

// 获取 Jsapi Ticket
var_dump('Jsapi Ticket: '.$ticket->getTicket());

// 获取 Wxcard Ticket
var_dump('Wxcard Ticket: '.$ticket->getTicket('wx_card'));
```

详细使用方式请参考 ``./_example/ticket.php`` 文件

## 获取公众号 JSSDK 配置

PHP:

```php
use Thenbsp\Wechat\Jssdk;

$jssdk = new Jssdk($accessToken);
$jssdk
    ->addApi('onMenuShareAppMessage')
    ->addApi('onMenuShareTimeline')
    ->enableDebug();

$configJSON = $jssdk->getConfig();
```

Javascript:

```
<script>
wx.config(<?php echo $configJSON; ?>);
</script>
```

详细使用方式请参考 ``./_example/jssdk.php`` 文件

## 获取微信服务器 IP

```php

use Thenbsp\Wechat\ServerIp;

$serverIp = new ServerIp($accessToken);

var_dump($serverIp->getServerIp());

```

详细使用方式请参考 ``./_example/serverip.php`` 文件

## 网页授权获取用户信息

网页授权使用标准的 [OAuth2][2] 协议授权，具体流程请看官方文档。

**注意：微信网页授权机制有两种 scope 类型： ``snsapi_base`` 和 ``snsapi_userinfo``，以 ``snsapi_base`` 发起的网页授权，不需要用户手动同意，但只能获取到 Openid，相反，以 ``snsapi_userinfo`` 发起的授权，需要用户后动同意，同意后可获取用户的 Openid, 昵称，头像，性别， 所在的等信息，具体请看官方文档**

```php
use Thenbsp\Wechat\OAuth\Client;

$client = new Client($wechat);

if( !isset($_GET['code']) ) {
    header('Location: '.$client->getAuthorizeUrl('Your callback url', 'snsapi_userinfo'));
} else {
    $client->getAccessToken($_GET['code']);
    
    var_dump($client->getUser());
}
```

详细使用方式请参考 ``./_example/oauth.php`` 文件

## 微信支付

微信支付需要配置公众号 ``支付目录`` 和 ``回调地址``，具体查看公众号管理后台 ``微信支付`` ->  ``开发配置``

微信支付分以下几种调用方式：

1、WeixinJSBridge invoke 方式

https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_7

2、Jssdk chooseWXPay方式

http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E5.8F.91.E8.B5.B7.E4.B8.80.E4.B8.AA.E5.BE.AE.E4.BF.A1.E6.94.AF.E4.BB.98.E8.AF.B7.E6.B1.82

3， 扫码支付（两种模式）

https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_1

以上两种方式都需要用户 openid，获取 Openid 可使用 网页授权 SDK

### WeixinJSBridge invoke 示例：

PHP:

```php
use Thenbsp\Wechat\Payment\JsBrandWCPayRequest;

$options = array(
    'body'          => 'iphone 6 plus',
    'total_fee'     => 1,
    'out_trade_no'  => date('YmdHis').mt_rand(10000, 99999),
    'notify_url'    => 'your are notify url',
    'openid'        => $_SESSION['openid']
);

$o = new JsBrandWCPayRequest($wechat, $options);

$configJSON = $o->getConfig();
```

Javascript:

``` javascript
var WXPayment = function() {
    if( typeof WeixinJSBridge === 'undefined' ) {
        alert('请在微信在打开页面！');
        return false;
    }
    WeixinJSBridge.invoke('getBrandWCPayRequest', <?php echo $configJSON; ?>, function(res) {
        switch(res.err_msg) {
            case 'get_brand_wcpay_request:cancel':
                alert('用户取消支付！');
                break;
            case 'get_brand_wcpay_request:fail':
                alert('支付失败！（'+res.err_desc+'）');
                break;
            case 'get_brand_wcpay_request:ok':
                alert('支付成功！');
                break;
            default:
                alert(JSON.stringify(res));
                break;
        }
    });
}
```

HTML:
```html
<button type="button" onclick="WXPayment()">支付 ￥<?php echo ($options['total_fee'] / 100); ?> 元</button>
```

详细使用方式请参考 ``./_example/payment-brandwcpayrequest.php`` 文件

### 扫码支付示例：

PHP:

```php
use Thenbsp\Wechat\Payment\QrcodeTemporary;

$options = array(
    'body' => 'iphone 6 plus',
    'total_fee' => 1,
    'out_trade_no' => date('YmdHis').mt_rand(10000, 99999),
    'notify_url' => 'your are notify url',
    'trade_type' => 'NATIVE'
);

$qrcode = new QrcodeTemporary($wechat, $options);

// 获取支付二维码
$payurl = $qrcode->getPayurl();
```

HTML:

```html
<img src="https://chart.googleapis.com/chart?cht=qr&chs=220x220&choe=UTF-8&chld=L|2&chl=<?php echo $payurl; ?>" />
```

详细使用方式请参考 ``./_example/payment-qrcode-temporary.php`` 文件

## 菜单管理

定义菜单

```php
use Thenbsp\Wechat\Menu\Button;
use Thenbsp\Wechat\Menu\ButtonCollection;

// 包含子菜单的按钮
$button1 = new ButtonCollection('菜单一');
$button1->addChild(new Button('点击', 'click', 'key_1'));
$button1->addChild(new Button('打开网页', 'view', 'http://www.163.com/'));
$button1->addChild(new Button('扫码', 'scancode_push', 'key_2'));

// 包含子菜单的按钮
$button2 = new ButtonCollection('菜单二');
$button2->addChild(new Button('系统拍照发图', 'pic_sysphoto', 'key_3'));
$button2->addChild(new Button('拍照或者相册发图', 'pic_photo_or_album', 'key_4'));
$button2->addChild(new Button('微信相册发图', 'pic_weixin', 'key_5'));

// 一级菜单
$button3 = new Button('菜单三', 'location_select', 'key_6');
```

创建菜单

```php
use Thenbsp\Wechat\Menu\Create;

$create = new Create($accessToken);
$create->add($button1);
$create->add($button2);
$create->add($button3);

try {
    $create->doCreate();
} catch (Exception $e) {
    exit($e->getMessage());
}

var_dump('菜单已创建成功');

```

查询菜单

```php
use Thenbsp\Wechat\Menu\Query;

/**
 * 查询接口
 */
$query = new Query($accessToken);

/**
 * 获取查询结果
 */
try {
    $result = $query->doQuery();
} catch (Exception $e) {
    exit($e->getMessage());
}

print_r($result);
```

删除菜单

```php
use Thenbsp\Wechat\Menu\Delete;

/**
 * 删除接口
 */
$delete = new Delete($accessToken);

/**
 * 执行删除
 */
try {
    $delete->doDelete();
} catch (Exception $e) {
    exit($e->getMessage());
}

var_dump('菜单删除建成功');
```

## 待续...


  [1]: http://www.php-fig.org/psr/psr-4/
  [2]: http://oauth.net/2/

  [1]: http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&text=http://code.1999.me/wechat-v2/_example/
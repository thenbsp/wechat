# 微信 SDK

以一种优雅的方式操作微信各种接口，遵循 [psr4 自动加载标准][1]！

SDK 包括以下功能：

- 获取公众号 AccessToken
- 获取公众号 Ticket
- 获取 JSSDK 配置
- 获取微信服务器 IP
- 网页授权获取用户信息
- 微信支付（JS invoke 方式）
- 微信支付（JS chooseWXPay 方式）
- 微信支付（扫码支付 模式一）
- 微信支付（扫码支付 模式二）

详细示例请看 ``./example`` 目录中的示例！

## 配置公众号 && 商户

```php
// 公众号配置
define('APPID', 'your appid');
define('APPSECRET', 'your appsecret');

// 商户配置
define('MCHID', 'your mchid');
define('MCHKEY', 'your mchkey');

// 支付成功通知 URL
define('NOTIFY_URL', 'http://example.com/your_are_notify.php');
```

## 一、获取公众号 AccessToken

http://mp.weixin.qq.com/wiki/11/0e4b294685f817b95cbed85ba5e82b8f.html

**注意：公众号 AccessToken 需要全局缓存维护，重复获取将导致旧 AccessToken 失效，因此将它存在 ``./Storage`` 目录下，需要设置该目录可写权限（出于安全考虑，请设置禁上浏览器直接访问该目录或将该目录移到处 Web 根目录以外）！**

```php
use Thenbsp\Wechat\Wechat;

$o = new Wechat(APPID, APPSECRET);

$accessToken = $o->getAccessToken();

var_dump($accessToken);
```

详细使用方式请参考 ``./example/access_token.php`` 文件

## 二、获取公众号 Ticket

http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E9.99.84.E5.BD.951-JS-SDK.E4.BD.BF.E7.94.A8.E6.9D.83.E9.99.90.E7.AD.BE.E5.90.8D.E7.AE.97.E6.B3.95

**注意：公众号 Ticket 需要全局缓存维护，重复获取将导致旧 Ticket 失效，因此将它存在 ``./Storage`` 目录下，需要设置该目录可写权限（出于安全考虑，请设置禁上浏览器直接访问该目录或将该目录移到处 Web 根目录以外）！**

公众号 ticket 分为 ``jsapi`` 和 ``wx_card``，getTicket 方法可传入一个可选参数

```php
use Thenbsp\Wechat\Wechat;

$o = new Wechat(APPID, APPSECRET);

$ticket = $o->getTicket();

var_dump($ticket);
```

详细使用方式请参考 ``./example/ticket.php`` 文件

## 三、获取公众号 JSSDK 配置

http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E9.99.84.E5.BD.951-JS-SDK.E4.BD.BF.E7.94.A8.E6.9D.83.E9.99.90.E7.AD.BE.E5.90.8D.E7.AE.97.E6.B3.95

JSSDK 配置文件依赖 AccessToken 和 Ticket，因此需要注入 Wechat 实例。

PHP:

```php
use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Wechat;

$wechat = new Wechat(APPID, APPSECRET);

$apis = array('onMenuShareTimeline', 'onMenuShareAppMessage');

$configJSON = Config::getJssdk($wechat, $apis, $debug = true, $asArray = false);
```

Javascript:

```
<script>
wx.config(<?php echo $configJSON; ?>);
</script>
```

详细使用方式请参考 ``./example/jssdk.php`` 文件

## 四、获取微信服务器 IP

http://mp.weixin.qq.com/wiki/0/2ad4b6bfd29f30f71d39616c2a0fcedc.html

```php

use Thenbsp\Wechat\Wechat;

$o = new Wechat(APPID, APPSECRET);

$ip = $o->getServerIp();

var_dump($ip);

```

详细使用方式请参考 ``./example/server_ip.php`` 文件

## 五、网页授权获取用户信息

http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html

网页授权使用标准的 [OAuth2][2] 协议授权，具体流程请看官方文档。

**注意：微信网页授权机制有两种 scope 类型： ``snsapi_base`` 和 ``snsapi_userinfo``，以 ``snsapi_base`` 发起的网页授权，不需要用户手动同意，但只能获取到 Openid，相反，以 ``snsapi_userinfo`` 发起的授权，需要用户后动同意，同意后可获取用户的 Openid, 昵称，头像，性别， 所在的等信息，具体请看官方文档**

```php
use Thenbsp\Wechat\OAuth;
use Thenbsp\Wechat\Exception\OAuthException;

$o = new OAuth(APPID, APPSECRET);

$callbackUrl = 'Your callback url';

if( !isset($_GET['code']) ) {
    $o->authorize($$callbackUrl, 'snsapi_userinfo');
} else {

    /**
     * 根据 code 换取 Token
     */
    try {
        $token = $o->getToken($_GET['code']);
    } catch (OAuthException $e) {
        exit($e->getMessage());
    }

    /**
     * 根据 Token 获取用户信息
     */
    $user = $o->getUser($token);
    
    var_dump($user);
}
```

详细使用方式请参考 ``./example/oauth.php`` 文件

## 六、微信支付

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
use Thenbsp\Wechat\OAuth;
use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Payment\Unifiedorder;

/**
 * 第 1 步：配置商品信息
 */
$bag = new Bag();
$bag->set('appid', APPID);
$bag->set('mch_id', MCHID);
$bag->set('notify_url', NOTIFY_URL);
$bag->set('body', 'iphone 6 plus');
$bag->set('out_trade_no', date('YmdHis').mt_rand(10000, 99999));
$bag->set('total_fee', 1);
$bag->set('openid', $_SESSION['openid']);

/**
 * 第 2 步：统一下单
 */
$unifiedorder = new Unifiedorder($bag, MCHKEY);

/**
 * 第 3 步：生成支付配置文件
 */
$configJSON = Config::getPaymentConfig($unifiedorder, $asArray = false);
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
<button type="button" onclick="WXPayment()">支付 ￥<?php echo ($bag->get('total_fee') / 100); ?> 元</button>
```

详细使用方式请参考 ``./example/payment_js_invoke.php`` 文件

### 扫码支付示例：

PHP:

```php
use Thenbsp\Wechat\Config;
use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Payment\Unifiedorder;

/**
 * 配置订单信息
 */
$bag = new Bag();
$bag->set('appid', APPID);
$bag->set('mch_id', MCHID);
$bag->set('notify_url', NOTIFY_URL);
$bag->set('body', 'iphone 6 plus');
$bag->set('out_trade_no', date('YmdHis').mt_rand(10000, 99999));
$bag->set('total_fee', 1); // 单位为 “分”
$bag->set('trade_type', 'NATIVE'); // NATIVE 时不需要 Openid

/**
 * 统一下单
 */
$unifiedorder = new Unifiedorder($bag, MCHKEY);

/**
 * 获取支付 URL（模式 2）
 */
$payurl = Config::getTemporaryPayurl($unifiedorder);
```

HTML:

```html
<img src="https://chart.googleapis.com/chart?cht=qr&chs=220x220&choe=UTF-8&chld=L|2&chl=<?php echo $payurl; ?>" />
```

详细使用方式请参考 ``./example/payment_qrcode_2.php`` 文件

## 待续...


  [1]: http://www.php-fig.org/psr/psr-4/
  [2]: http://oauth.net/2/
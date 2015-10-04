# 网页授权

微信网页授权需要在公众号后台设置回调域名。

网页授权机制有两种 scope 类型： snsapi_base 和 snsapi_userinfo，以 snsapi_base 发起的网页授权，不需要用户手动同意，但只能获取到 Openid，相反，以 snsapi_userinfo 发起的授权，需要用户后动同意，同意后可获取用户的 Openid, 昵称，头像，性别， 所在的等信息，具体请看官方文档。

## 业务流程

1，检测业务中的会话状态（检测登录），如果会话中已包含用户信息（已登录）则直接到步骤 4，否则步骤 2。
2，如果当前没有会话（还没有登录），则跳转到授权页。
3，用户同意授权，页面将自动跳转至回调（callback）页并带上 code，通过 code 换取 accessToken 和 openid，通过 accessToken 和 openid 获取用户信息，存入会话，再到步骤 4.
4，跳转至最终业务页面

跳转到授权页

```php
use Thenbsp\Wechat\OAuth\Client;

$client = new Client($wechat);

$callbackUrl = 'Your callback url';

header('Location: '.$client->getAuthorizeUrl($callbackUrl));
```

根据 Code 换取 AccessToken

```php

if( !isset($_GET['code']) ) {
    exit('Invalid Request');
}

$accessToken = $client->getAccessToken($_GET['code']);
```

获取用户信息

```php
$user = $client->getUser();
```

完整流程

```php
use Thenbsp\Wechat\OAuth\Client;

$client = new Client($wechat);

$callbackUrl = 'Your callback url';

if( !isset($_GET['code']) ) {
    header('Location: '.$client->getAuthorizeUrl($callbackUrl));
} else {
    $client->getAccessToken($_GET['code']);
    var_dump($client->getUser());
}
```

# 微信支付 - 共享收货地址

共享收货地址依赖于 **用户AccessToken** 对象，因此必需先授权获取 AccessToken：

```php
use Thenbsp\Wechat\OAuth\Client;

$client = new Client($wechat);

$callbackUrl = 'Your callback url';

if( !isset($_GET['code']) ) {
    header('Location: '.$client->getAuthorizeUrl($callbackUrl));
} else {
    $accessToken = $client->getAccessToken($_GET['code']);
}
```

注入用户 AccessToken 并获取配置：

```php
$o = new Address($accessToken);

$configJSON = $o->getConfig();
```

将配置注入 WeixinJSBridge：

```javascript
var getAddress = function() {
    WeixinJSBridge.invoke('editAddress', <?php echo $configJSON; ?>, function(res) {
        switch(res.err_msg) {
            case 'edit_address:ok':
                alert('获取编辑收货地址成功！');
                // res.userName 姓名
                // res.telNumber 手机号
                // res.proviceFirstStageName 省份
                // res.addressCitySecondStageName 城市
                // res.addressDetailInfo 详细地址
                // res.addressPostalCode 邮编
                // res.nationalCode 收货地址国家码
                break;
            case 'edit_address:fail':
                alert('获取编辑收货地址失败！');
                break;
            case 'edit_address:cancel':
                alert('您已取消获取地址！');
                break;
            default:
                alert(JSON.stringify(res));
                break;
        }
    }
}
```

```html
<button type="button" onclick="getAddress()">选择收货地址</button>
```

详细用法请查看 [/documentation/payment-address.md](/documentation/payment-address.md)
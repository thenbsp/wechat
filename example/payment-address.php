<?php

require './example.php';

session_start();

use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\Payment\Address;

if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
    exit('请在微信中打开');
}

define('AUTHKEY', 'user');

/**
 * 共享收货地址需要 **用户 AccessToken** 对象，因此必需授权
 */
if( !isset($_SESSION[AUTHKEY]) ) {

    $client = new Client($wechat);

    if( !isset($_GET['code']) ) {
        $callback = EXAMPLE_URL.'payment-address.php';
        header('Location: '.$client->getAuthorizeUrl($callback));
    } else {
        $token = $client->getAccessToken($_GET['code']);
        $_SESSION[AUTHKEY] = $token;
    }
}

/**
 * 生成共享收货地址 JS 配置文件
 */
$o = new Address($_SESSION[AUTHKEY]);

$configJSON = $o->getConfig();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wechat SDK</title>
</head>
<body ontouchstart="">

<h1>共享收货地址&nbsp;&nbsp;<a href="javascript:;" onclick="window.location.reload()">刷新</a></h1>

<button type="button" onclick="getAddress()" style="font-size:16px;height:38px;">选择收货地址</button>

<ul>
<li><strong>姓名：</strong><span id="name"></span></li>
<li><strong>手机：</strong><span id="mobile"></span></li>
<li><strong>省份：</strong><span id="provice"></span></li>
<li><strong>城市：</strong><span id="city"></span></li>
<li><strong>详细地址：</strong><span id="address"></span></li>
<li><strong>邮政编码：</strong><span id="postalCode"></span></li>
<li><strong>收货地址国家码：</strong><span id="nationalCode"></span></li>
</ul>

<script>
var getAddress = function() {
    if( typeof WeixinJSBridge === 'undefined' ) {
        alert('请在微信在打开页面！');
        return false;
    }
    WeixinJSBridge.invoke('editAddress', <?php echo $configJSON; ?>, function(res) {
        alert(JSON.stringify(res));
        switch(res.err_msg) {
            case 'edit_address:ok':
                alert('获取编辑收货地址成功！');
                document.getElementById('name').innerHTML = res.userName;
                document.getElementById('mobile').innerHTML = res.telNumber;
                document.getElementById('provice').innerHTML = res.proviceFirstStageName;
                document.getElementById('city').innerHTML = res.addressCitySecondStageName;
                document.getElementById('address').innerHTML = res.addressDetailInfo;
                document.getElementById('postalCode').innerHTML = res.addressPostalCode;
                document.getElementById('nationalCode').innerHTML = res.nationalCode;
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
    });
}
</script>
</body>
</html>
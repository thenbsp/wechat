<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Wechat SDK</title>
<style>
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,select,p,blockquote,th,td,header,hgroup,nav,section,article,aside,footer,figure,figcaption,menu,button {
font-size:15px; font-family:"Helvetica Neue",Helvetica,STHeiTi,sans-serif; line-height: 1.4; margin: 0; padding: 0; }
ul,dl,ol { list-style-type:none; }
a { color: #00a5e0; text-decoration: none; }
a:active,
a:focus,
a:hover { color: #d60000; outline: none; }
#container{ margin: 20px; }
#container > h3{ font-size: 20px; line-height: 1; margin: 20px 0 0 0; }
#container > ul{ padding-left: 20px; list-style-type: square; }
#container > ul > li{ margin-left: 20px; }
#container > ul,
#container > p{ margin: 10px 0 20px 0;  }
</style>
</head>
<body>

<div id="container">

    <h3>简介</h3>
    <p>微信公众平台第三方 SDK 版，简单，优雅、健壮，遵循 psr4 自动加载标准！</p>

    <h3>作者</h3>
    <ul>
        <li>By thenbsp（冯特罗）</li>
        <li>Email: <a href="mailto:thenbsp@gmail.com">thenbsp@gmail.com</a></li>
        <li>Github: <a href="https://github.com/thenbsp/wechat" target="_blank">https://github.com/thenbsp/wechat</a></li>
    </ul>

    <h3>安装</h3>
    <ul>
        <li>composer require thenbsp/wechat</li>
    </ul>

    <h3>组件</h3>
    <ul>
        <li>Http</li>
        <li>Cache</li>
        <li>Options</li>
        <li>Serialize</li>
    </ul>

    <h3>基础功能</h3>
    <ul>
        <li><a href="./wechat.php" target="_blank">配置 Wechat 对象</a></li>
        <li><a href="./access_token.php" target="_blank">获取 AccessToken</a></li>
        <li><a href="./ticket.php" target="_blank">获取 Ticket</a></li>
        <li><a href="./serverip.php" target="_blank">获取微信服务器 IP</a></li>
        <li><a href="./jssdk.php" target="_blank">生成 jssdk 配置</a></li>
        <li><a href="./oauth.php" target="_blank">网页授权获取用户信息</a></li>
    </ul>

    <h3>自定义菜单</h3>
    <ul>
        <li><a href="./menu-create.php" target="_blank">创建菜单</a></li>
        <li><a href="./menu-query.php" target="_blank">查询菜单</a></li>
        <li><a href="./menu-delete.php" target="_blank">删除菜单</a></li>
    </ul>

    <h3>微信支付</h3>
    <ul>
        <li><a href="./payment-unifiedorder.php" target="_blank">统一下单</a></li>
        <li><a href="./payment-brandwcpayrequest.php" target="_blank">公众号支付-BrandWCPayRequest</a></li>
        <li><a href="./payment-choosewxpay.php" target="_blank">公众号支付-ChooseWXPay</a></li>
        <li><a href="./payment-qrcode-forever.php" target="_blank">扫码支付-模式一</a></li>
        <li><a href="./payment-qrcode-temporary.php" target="_blank">扫码支付-模式二</a></li>
    </ul>

    <h3>其它功能陆续开发中...</h3>

</div>

</body>
</html>
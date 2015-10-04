 # 微信服务器 IP

 如果公众号基于安全等考虑，需要获知微信服务器的IP地址列表，以便进行相关限制，可以通过该接口获得微信服务器IP地址列表。

 ```php
use Thenbsp\Wechat\ServerIp;

$serverIp = new ServerIp($accessToken);

var_dump($serverIp->getServerIp());
 ```
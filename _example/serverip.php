<?php

require './_example.php';

use Thenbsp\Wechat\ServerIp;

$serverIp = new ServerIp($accessToken);

echo '<pre>';
var_dump($serverIp->getServerIp());
echo '</pre>';
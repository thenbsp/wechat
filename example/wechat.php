<?php

require './example.php';

// 公众号 Appid
var_dump('Appid: '.$wechat['appid']);
echo '<br />';

// 公众号 Appsecret
var_dump('mchid: '.$wechat['mchid']);
echo '<br />';

// 全部选项
var_dump($wechat->getOptions());

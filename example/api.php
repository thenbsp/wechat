<?php

require './example.php';

// use Thenbsp\Wechat\Util\Http;
// use Thenbsp\Wechat\Util\Request;
// use Thenbsp\Wechat\Util\Serialize;

// 生成带参数的二维码
// $options = array(
//     'expire_seconds'    => 604800,
//     'action_name'       => 'QR_SCENE',
//     'action_info'       => array(
//         'scene' => array(
//             'scene_id' => '123456'
//         )
//     )
// );

// $options = Serialize::encode($options, 'json');

// $request = Http::post('https://api.weixin.qq.com/cgi-bin/qrcode/create', array(
//     'query' => array(
//         'access_token' => $accessToken->getAccessToken()
//     ),
//     'body' => $options
// ));

// $response = $request->json();
// echo sprintf('<img  src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=%s" />', $response['ticket']);

// 记录请求内容
$request = Request::createFromGlobals();
$cache->set(date('YmdHis'), $request->getContent());

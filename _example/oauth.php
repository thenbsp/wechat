<?php

require './_example.php';

/**
 * 只能在微信中打开
 */
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
    exit('请在微信中打开');
}

/**
 * 微信 OAuth 的业务流程应该是：
 *
 * 1，检测业务中的会话状态（检测登录），如果会话中已包含用户信息（已登录）则直接到步骤 4，否则步骤 2。
 * 2，如果当前没有会话（还没有登录），则跳转到授权页。
 * 3，用户同意授权，页面将自动跳转至回调（callback）页并带上 code，通过 code 换取 accessToken 和 openid，
 *    通过 accessToken 和 openid 获取用户信息，存入会话，再到步骤 4.
 * 4，跳转至最终业务页面
 *
 * 下面是一个简单的示例：
 */
session_start();

// 认证用户 Session key
const AUTHKEY = 'current_user';

// 如果没有登录，则去授权
if( !isset($_SESSION[AUTHKEY]) ) {

    // 回调地址，示例中为本页
    $callback = EXAMPLE_URL.'_example/oauth.php';

    // OAuth 对象，需传入 Wechat 对象
    $client = new \Thenbsp\Wechat\OAuth\Client($wechat);

    // 跳转到授权页
    if( !isset($_GET['code']) ) {
        header('Location: '.$client->getAuthorizeUrl($callback, 'snsapi_userinfo'));
    }
    // 根据 code 换取 accessToken
    else {
        // Token 里已经包含了 access_token/openid 等信息，如果需要 openid，就不需要再 getUser
        $token = $client->getAccessToken($_GET['code']);
        // 如果需要，可以用 getUser 方法获了当前用户信息
        $user = $client->getUser();

        $cache->set('oauth_demo_'.$user['openid'], $user->getOptions());
        // 写入会话
        $_SESSION[AUTHKEY] = $user;
    }
}

// 用户信息
echo sprintf('<h3><img src="%s" /></h3>', $_SESSION[AUTHKEY]['headimgurl']);
echo sprintf('<h3>Openid：%s</h3>', $_SESSION[AUTHKEY]['openid']);
echo sprintf('<h3>昵称：%s</h3>', $_SESSION[AUTHKEY]['nickname']);
echo sprintf('<h3>性别：%s</h3>', ($_SESSION[AUTHKEY]['sex'] == 1 ? '男' : '女'));
echo sprintf('<h3>语言：%s</h3>', $_SESSION[AUTHKEY]['language']);
echo sprintf('<h3>国家：%s</h3>', $_SESSION[AUTHKEY]['country']);
echo sprintf('<h3>省份：%s</h3>', $_SESSION[AUTHKEY]['province']);
echo sprintf('<h3>城市：%s</h3>', $_SESSION[AUTHKEY]['city']);

// 获取全部信息
// echo '<pre>';
// var_dump($_SESSION[AUTHKEY]->getOptions());
// echo '</pre>';

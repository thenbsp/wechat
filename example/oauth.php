<?php

require './config.php';

use Thenbsp\Wechat\OAuth;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Exception\OAuthException;

/**
 * 微信 OAuth 的业务流程应该是：
 *
 * 1，检测会话状态，如果会话中有用户信息则进行第 3 步，否则发起授权
 * 2，授权完成后，将用户信息（包括 Token 信息）存入会话，再进行第 3 步
 * 3，跳转至最终业务页面
 *
 * 下面是一个简单的示例：
 */

// 会话标识
define('TOKEN', 'auth_token');

/**
 * 示例代码，实际应用请修改为自己的逻辑
 * START
 */
// 是否登录
function isAuthorize() {
    return isset($_SESSION[TOKEN]);
}

// 设置登录状态
function setAuthorize($tokenData) {
    $_SESSION[TOKEN] = serialize($tokenData);
}

// 获取会话信息
function getAuthorize() {
    if( isAuthorize() ) {
        return unserialize($_SESSION[TOKEN]);
    }
}

// 请除登录状态
function clearAuthorize() {
    unset($_SESSION[TOKEN]);
}
/**
 * END
 */

$o = new OAuth(APPID, APPSECRET);

/**
 * 授权流程
 */
if( !isAuthorize() ) {
    // 跳转至授权页
    if( !isset($_GET['code']) ) {
        $o->authorize(Util::currentUrl(), 'snsapi_userinfo');
    }
    // Code 换取 Token
    else {
        try {
            $token = $o->getToken($_GET['code']);
        } catch (OAuthException $e) {
            exit($e->getMessage());
        }
        setAuthorize($token);
        header('Location: '.Util::currentUrl());
    }
}

/**
 * 根据 Token 来获取用户信息
 */
$token = getAuthorize();

$user = $o->getUser($token);

echo '<pre>';
var_dump($token);
var_dump($user);
echo '</pre>';

/**
 * 刷新 TOKEN
 */
if( !$o->accessTokenIsValid($token) ) {
    $token = $o->refreshToken($token->refresh_token);
    setAuthorize($token);
}

?>

<h1><a href="javascript:;" onclick="window.location.reload()">刷新</a></h1>

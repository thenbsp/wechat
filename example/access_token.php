<?php

require './example.php';

use Thenbsp\Wechat\Wechat\Exception\AccessTokenException;

/**
 * 失败时抛出 AccessTokenException
 */
try {
    $tokenString = $accessToken->getTokenString();
} catch (AccessTokenException $e) {
    exit($e->getMessage());
}

var_dump($tokenString);

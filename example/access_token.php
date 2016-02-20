<?php

require './example.php';

// 失败时抛出 AccessTokenException
try {
    $tokenString = $accessToken->getTokenString();
} catch (\Exception $e) {
    exit($e->getMessage());
}

var_dump($tokenString);

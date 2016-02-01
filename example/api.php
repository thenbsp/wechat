<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Serializer;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

if( !$request->getContent() ) {
    exit;
}

$logger->debug($request->getClientIp(), Serializer::xmlDecode($request->getContent()));
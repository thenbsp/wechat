<?php

namespace Thenbsp\Wechat\Util;

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\JSON;

class Http
{
    /**
     * GET Request
     */
    public static function get($url, $params = array(), $jsonDecode = true)
    {
        $response = static::doRequest('GET', $url, $params);

        return $jsonDecode ? JSON::decode($response) : $response;
    }

    /**
     * POST Request
     */
    public static function post($url, $params = array(), $jsonDecode = true)
    {
        $response = static::doRequest('POST', $url, $params);

        return $jsonDecode ? JSON::decode($response) : $response;
    }

    /**
     * Request URL Via Curl
     */
    public static function doRequest($method, $url, $params = array())
    {
        $request = curl_init($url);

        switch (strtoupper($method)) {
            case 'HEAD':
                curl_setopt($request, CURLOPT_NOBODY, true);
                break;
            case 'GET':
                curl_setopt($request, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($request, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($request, CURLOPT_POSTFIELDS, $params);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($request);

        if( empty($response) ) {
            
            $errno = curl_errno($request);
            $error = curl_error($request);

            $errorMessage = 'Request failure';

            if($error != 0) {
                $errorMessage .= " ({$errno}: {$error})";
            }

            throw new \Exception($errorMessage);
        }

        curl_close($request);

        return $response;
    }
}
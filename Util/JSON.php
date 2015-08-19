<?php

namespace Thenbsp\Wechat\Util;

/**
 * Json encode/decode
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/07/23
 */
defined('JSON_UNESCAPED_UNICODE') || define('JSON_UNESCAPED_UNICODE', 0);

class JSON
{
    /**
     * Json Encode
     */
    public static function encode($data, $encoding = 0)
    {
        $encoding |= JSON_UNESCAPED_UNICODE;

        return json_encode($data, $encoding);
    }

    /**
     * Json Decode
     */
    public static function decode($data, $assocArray = false)
    {
        return json_decode($data, $assocArray);
    }

    /**
     * Json last error
     */
    public static function error()
    {
        $error = json_last_error();
        if (JSON_ERROR_NONE !== $error) {
            switch ($error) {
                case JSON_ERROR_DEPTH:
                    return 'The maximum stack depth has been exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    return 'Invalid or malformed JSON';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    return 'Control character error, possibly incorrectly encoded';
                    break;
                case JSON_ERROR_SYNTAX:
                    return 'Syntax error';
                    break;
                case JSON_ERROR_UTF8:
                    return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    return "Unknown error ($error)";
                    break;
            }
        }
    }
}
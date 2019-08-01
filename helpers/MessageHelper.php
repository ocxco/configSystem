<?php
/**
 * Created by PhpStorm.
 * User: xuechaoc
 * Date: 2019-04-25
 * Time: 18:40
 */

namespace app\helpers;

class MessageHelper
{
    public static $msg = [];

    public static function msg($msg, $type = 'success')
    {
        self::$msg[] = compact('msg', 'type');
        return true;
    }

    public static function success($msg)
    {
        self::msg($msg, 'success');
    }

    public static function danger($msg)
    {
        self::msg($msg, 'danger');
    }

    public static function info($msg)
    {
        self::msg($msg, 'info');
    }

}
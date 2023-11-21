<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\Functions\stab;

class Functions
{

    //unixTimePhptoJava
    public static function unixTimePhpToJava($time)
    {
        $conTime = $time * 1000;
        return $conTime;
    }

    public static function unixTimeJavaToPhp($time)
    {
        $conTime = $time / 1000;
        return $conTime;
    }

    public static function formatDateToPhp($date)
    {
        $date_format = substr($date, 0, strlen($date) - 3);
        $time_format = substr($date, 12, strlen($date));
        return date("Y-m-d", strtotime(substr($date_format, 0, strlen($date_format) - 8))).' '.date("H:i:s", strtotime($time_format));
    }

}
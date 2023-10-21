<?php

namespace App\Helpers;

class NumberFormatHelper
{
    public static function currency($number)
    {
        $result = number_format($number, 2, ",", ".");
        return $result;
    }

    public static function decimal($number)
    {
        $result = number_format($number, 0, ",", ".");
        return $result;
    }
}

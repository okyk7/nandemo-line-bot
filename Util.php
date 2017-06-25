<?php

/**
 * Util
 */
class Util
{
    /**
     * dump to log
     * @param object $var
     */
    public static function dump($var)
    {
        error_log(var_export($var, true));
    }

    /**
     * dump to error_log
     * @param object $var
     */
    public static function error($var)
    {
        error_log(var_export($var, true));
    }
}

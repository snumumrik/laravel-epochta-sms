<?php

/**
 * Clear phone number
 */
if (! function_exists('epochta_clear_phone')) {
    /**
     * @param $str
     * @return string
     */
    function epochta_clear_phone($str)
    {
        return preg_replace('/[^0-9]/si', '', $str);
    }
}

<?php
/**
 * Register a debugger. This debugger shows output in Laravel style and
 * can take three parameters, the message, a variable, and which mode
 * What make is really simple is that it's so small yet powerful.
 */
if (!function_exists('debugger')) {
    function debugger($message, $variable = '', $mode = "DEBUG")
    {        
        if (get_option('forms_api_enable_debugger') !== "1") {
            return;
        }

        $serverAddr     = $_SERVER['SERVER_ADDR'];

        $dateTimeFormat = date('Y-m-d H:i:s');

        $prefix         = "[$dateTimeFormat] $serverAddr.$mode: ";

        if (is_array($variable) or is_object($variable)) {
            $variable = print_r($variable, 1);
        } else if (gettype($variable) == 'boolean') {
            $variable = "(Boolean: $variable)";
        }

        $logPath = ABSPATH . "wp-content/plugins/forms-api/";
                                
        // file_put_contents($logPath . 'log_' . date("dmY") . '.log', $prefix . $message . $variable . "\n", FILE_APPEND);
        file_put_contents($logPath . 'forms-api.log', $prefix . $message . $variable . "\n", FILE_APPEND);
    }
}

/**
 * TODO
 * 
 * - Add option to output date according to locale
 */
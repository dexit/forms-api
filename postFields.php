<?php

/**
 * Post to an endpoint. $system determines if it goes to 'test' or 'live' SSL
 * turned off intentionally because causes silent failures on localhost.
 * Both Elementor and Gravity Forms call this exact routine.
 */
if (!function_exists('postFields')) {

    function postFields($fields, $system = 'test')
    {                
        $url = get_option("forms_api_{$system}_url");

        $api_token = get_option("forms_api_{$system}_token");

        if (!$url || !$api_token) {
            return;
        }

        $fields['api_token'] = $api_token;
        $fields['referrer'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $fields['remote_addr'] = $_SERVER['REMOTE_ADDR'];
		$fields['http_host']	= "http://$_SERVER[HTTP_HOST]";
        
        debugger("Posting {$system} fields to $url");

        $request  = new WP_Http();

        add_filter('https_ssl_verify', '__return_false');
	
        $response = $request->post($url, array('body' => $fields));
    
        debugger("Response: " , $response['body']);	        
    }

}

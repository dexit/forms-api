<?php

/**
 * Post to an endpoint. $system determines if it goes to 'test' or 'live' SSL
 * turned off intentionally because causes silent failures on localhost.
 * Both Elementor and Gravity Forms call this exact routine.
 */
if (!function_exists('postFields')) {
    function postFields($fields, $system = 'test')
    {                
        $fields['api_token'] = get_option("forms_api_{$system}_token");

        $url = get_option("forms_api_{$system}_url");

        debugger("Posting {$system} fields to $url");

        $request  = new WP_Http();

        add_filter('https_ssl_verify', '__return_false');
	
        $response = $request->post($url, array('body' => $fields));
    
        debugger("Response: " , $response['body']);	        
    }
}

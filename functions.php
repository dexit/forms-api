<?php

// -------------
// BIS Forms API
// -------------

// Turn off SSL validation - needed for localhost test environments where there is no certificate installed
add_filter('https_ssl_verify', '__return_false');

/**
 * Return the Leads API URL based on the script IP address
 *
 * @return string
 */
function getLeadsApiUrl()
{
	if (BIS_MODE == 'production') {
		return "https://bis.taxconsulting.co.za/api/v1";
	}
    
	if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {
		return "http://bisdev.test/api/v1";
    }
    
	return "https://bisdev.taxconsulting.co.za/api/v1";
}

/*
 * gform_pre_submission
 * 
 * Submit a placeholder lead to BIS so that we can receive the next lead ID for 
 * local storage. Placeholder data is used to tell the API that just a new lead
 * must be created. The follow up routine then updated the actual lead.
 *
 */
add_action('gform_pre_submission', 'submit_placeholder_lead', 10, 2);
function submit_placeholder_lead($form)
{

	// debugger( 'Forms API submit placeholder lead to BIS, $entry: ', $entry );		

	$api_url = getLeadsApiUrl() . '/leads';

	$body = [
		'api_token'      => 'aq74o1t71XL3Rb4CQLBNsM7r8utTJwkNcLUBRJfOoxsrS1T0XaJq3hrx76Lx',
		'gform_id'		 => 'placeholder',
		'remote_addr'	 => $_SERVER['REMOTE_ADDR'],
	];

	$request  = new WP_Http();
	$response = $request->post($api_url, array('body' => $body));
	$result = $response['body'];

	debugger("BIS API Lead Placeholder response in gform_pre_submission: ", $result);
	
	$id = getFieldIdByLabel($form, 'bis_lead_id');
	$_POST['input_' . $id] = json_decode($result)->lead_id;
}

function getFieldIdByLabel($form, $label)
{
	$fields = $form['fields'];
	foreach ($fields as $field) {		
		if ($field['label'] == "bis_lead_id") {
			return $field->id;
		}
	}
}

/*
 * Submit a lead to BIS
 *
 */
add_action('gform_after_submission', 'post_to_third_party', 10, 2);
function post_to_third_party($entry, $form)
{

	debugger('Forms API post to third party, $entry: ', $entry);
	// debugger( 'Forms API post to third party, $form: ', $form );

	$api_url = getLeadsApiUrl() . '/leads';
	debugger("The API URL is $api_url");

	$header = getHeader($entry, $form);
	$formData = getForm($entry, $form);
	$body = array_merge($header, $formData);

	$request  = new WP_Http();
	$response = $request->post($api_url, array('body' => $body));
	$result = $response['body'];

	debugger("BackOffice API response in gform_after_submission: ", $result);
}

function getHeader($entry, $form)
{

	$gform_entry_id = $entry['id'];
	$gform_form_id = $form['id'];
	$formLink = "wp-admin/admin.php?page=gf_entries&view=entry&id=$gform_form_id&lid=$gform_entry_id";

	return [
		'api_token'      => 'aq74o1t71XL3Rb4CQLBNsM7r8utTJwkNcLUBRJfOoxsrS1T0XaJq3hrx76Lx',
		'gform_id'       => $entry['id'] . '|' . $form['id'],
		'website'		 => "http://$_SERVER[HTTP_HOST]",
		'referrer'       => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]$formLink",
		'remote_addr'	 => $_SERVER['REMOTE_ADDR'],
	];
}

function getForm($entry, $form)
{
	foreach ($form['fields'] as $field) {
		$fields[toLower($field->label)] = $entry[$field->id];
	}
	return $fields;
}

function toLower($label)
{
	return strtolower(str_replace(' ', '_', $label));
}

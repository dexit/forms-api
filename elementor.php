<?php

/**
 * See if we have the correct Elementor form, and if so, start building the fields. Once
 * the fields are built, they are sent to the test and live endpoints where a
 * body is contructed and the API security / token is added to the request
 * 
 * https://developers.elementor.com/forms-api/#Form_New_Record_Action
 */
add_action( 'elementor_pro/forms/new_record', function( $record, $handler ) {    
    // Make sure its our form
    $form_name = $record->get_form_settings( 'form_name' );    
    if ( get_option('forms_api_elementor_pro_form_name') !== $form_name ) {
        debugger("An Elementor form called '$form_name' does not match the API settings so taking no action");
        return;
    }

    $raw_fields = $record->get( 'fields' );

    $fields = [];

    foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = $field['value'];
    }

    postFields($fields, 'test');

    postFields($fields, 'live');
    
}, 10, 2 );

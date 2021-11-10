<?php

/**
 * Plugin Name: Forms API
 * Plugin URI: https://fintechsystems.net
 * Description: A generic forms API that posts Elementor Pro and Gravity Forms to any endpoint at any 3rd party site
 * Version: 0.0.1
 * Author: Eugene van der Merwe
 * Author URI: https://github.com/eugenevdm
 * License: MIT
 */

/**
 * Registers the settings and adds an admin_init action
 * 
 * Settings available:
 * 
 * forms_api_option_api_token
 * forms_api_option_api_url
 * forms_api_option_elementor_pro_form_name
 * forms_api_option_enable_debugger
 * 
 */
function forms_api_register_settings()
{
    require_once('debugger.php');
    require_once('elementor.php');
    require_once('postFields.php');
    
    add_option('forms_api_test_token', '1234');
    register_setting(
        'forms_api_options_group',
        'forms_api_test_token',
        'forms_api_callback'
    );

    add_option('forms_api_test_url', 'https://my-salescrm.test/api/v1/leads');
    register_setting(
        'forms_api_options_group',
        'forms_api_test_url',
        'forms_api_callback'
    );

    add_option('forms_api_live_token', '');
    register_setting(
        'forms_api_options_group',
        'forms_api_live_token',
        'forms_api_callback'
    );

    add_option('forms_api_live_url', '');
    register_setting(
        'forms_api_options_group',
        'forms_api_live_url',
        'forms_api_callback'
    );

    add_option('forms_api_elementor_pro_form_name', 'New Form');
    register_setting(
        'forms_api_options_group',
        'forms_api_elementor_pro_form_name',
        'forms_api_callback'
    );

    add_option('forms_api_option_enable_debugger', 1);
    register_setting(
        'forms_api_options_group',
        'forms_api_enable_debugger',
        'forms_api_callback'
    );
}
add_action('admin_init', 'forms_api_register_settings');

/**
 * Adds a new menu under Settings to manage Forms API Settings
 */
function forms_api_register_options_page()
{
    add_options_page(
        'Forms API Settings',
        'Forms API',
        'manage_options',
        'forms-api-settings',
        'forms_api_options_page'
    );
}
add_action('admin_menu', 'forms_api_register_options_page');

/**
 * Output the options for the settings page
 */
function forms_api_options_page()
{
    $enable_debugger = get_option('forms_api_enable_debugger');
?>
    <div>
        <h2>Forms API Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('forms_api_options_group'); ?>
            <table>

                <tr>
                    <td>Test Token</td>
                    <td>
                        <input type="text" name="forms_api_test_token" size="70" value="<?php echo get_option('forms_api_test_token'); ?>" />
                    </td>
                </tr>

                <tr>
                    <td>Test URL</td>
                    <td>
                        <input type="text" name="forms_api_test_url" size="40" value="<?php echo get_option('forms_api_test_url'); ?>" />
                    </td>
                </tr>

                <tr>
                    <td>Live Token</td>
                    <td>
                        <input type="text" name="forms_api_live_token" size="70" value="<?php echo get_option('forms_api_live_token'); ?>" />
                    </td>
                </tr>

                <tr>
                    <td>Live URL</td>
                    <td>
                        <input type="text" name="forms_api_live_url" size="40" value="<?php echo get_option('forms_api_live_url'); ?>" />
                    </td>
                </tr>

                <tr>
                    <td>Elementor Pro Form Name</td>
                    <td>
                        <input type="text" name="forms_api_elementor_pro_form_name" size="40" value="<?php echo get_option('forms_api_elementor_pro_form_name'); ?>" />
                    </td>
                </tr>

                <tr>
                    <td><label for="enable_debugger_checkbox">Enable debugger<label></td>
                    <td>
                        <input type="checkbox" id="enable_debugger_checkbox" name="forms_api_enable_debugger" value="1" <?php echo ($enable_debugger == true) ? 'checked' : '' ?> />
                    </td>
                </tr>

            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
} ?>
<?php

// Placeholder - debugger used to go here
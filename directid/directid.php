<?php
/*
Plugin Name: DirectID Widget
Plugin URI: https://developers.direct.id
Description: Provides a shortcode and action for simplified integration of DirectID into Wordpress. You will need to enter your Client ID and secret on the settings page.
Author: Jack Schofield
Version: 1.2
Author URI: http://www.miicard.com
*/

$dir = plugin_dir_path(__FILE__);
require_once $dir . 'oAuthManager.php';
require_once $dir . 'apiManager.php';
require_once $dir . 'encryptionManager.php';
require_once $dir . 'errorManager.php';
require_once $dir . 'admin.php';
require_once $dir . 'urlParamManager.php';

function init_directid_widget(){
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_script('did-widget-loader', $plugin_url . '/js/didWidgetLoader.js');
    wp_enqueue_style('did-widget-loader', $plugin_url . '/css/didWidgetLoader.css');

    echo '<script type="text/javascript"> var ajaxurl = "' . admin_url('admin-ajax.php') . '"; </script>';

    echo '<div id="did-widget-container">
            <div class="did-widget-loader"> 
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                </svg>
            </div>
            <noscript>
                <h3>Your browser does not support javascript.</h3>
                <h4>Please enable javascript to continue.</h4>
            </noscript>
           </div>';
}

function directid_widget_ajax_callback(){
    $settings = get_option('didWidgetSettings');

    $didToken = 'token not set';

    //As this is being performed via Ajax params are wired through by didWidgetLoader.js
    $didTokenParam = getParamFromPostRequest('didToken');
    
    if($didTokenParam){
        $didToken = $didTokenParam;
    }
    else {
        $didCustomerReference = getParamFromPostRequest('didCustomerReference');
        $didEmail = urldecode(getParamFromPostRequest('didEmail'));

        if(!$didCustomerReference) $didCustomerReference = 'defaultuser';
        if(!$didEmail) $didEmail = null;

        $didToken = directid_get_user_session_token($didCustomerReference, $didEmail);
    };


    echo '<link href="' . $settings['cdnpath'] . 'directid.min.css" rel="stylesheet" type="text/css">';
    echo '<div id="did" data-token="' . $didToken . '"></div>';
    echo '<script src="' . $settings['cdnpath'] . 'directid.min.js"></script>';
    wp_die();
}

add_shortcode('directid-widget', 'init_directid_widget');
add_action('directid_widget', 'init_directid_widget');

add_action('wp_ajax_directid_widget_ajax', 'directid_widget_ajax_callback');
add_action('wp_ajax_nopriv_directid_widget_ajax', 'directid_widget_ajax_callback');

?>
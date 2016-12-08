<?php

class DirectIdSettings
{
    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'DirectID Widget Settings',
            'manage_options',
            'directid-setting-admin',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->didWidgetSettings = get_option('didWidgetSettings');

        ?>
        <div class="wrap">
            <h2>DirectID Widget Settings</h2>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('directid_option_group');
                do_settings_sections('directid-setting-admin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'directid_option_group', // Option group
            'didWidgetSettings', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'directid_setting_section_id', // ID
            'DirectID Widget Settings', // Title
            array($this, 'print_section_info'), // Callback
            'directid-setting-admin' // Page
        );

        add_settings_field(
            'clientid', // ID
            'Client ID', // Title
            array($this, 'clientid_callback'), // Callback
            'directid-setting-admin', // Page
            'directid_setting_section_id' // Section
        );

        add_settings_field(
            'title',
            'Secret Key',
            array($this, 'secretkey_callback'),
            'directid-setting-admin',
            'directid_setting_section_id'
        );

        add_settings_field(
            'company',
            'Company Name',
            array($this, 'companyName_callback'),
            'directid-setting-admin',
            'directid_setting_section_id'
        );

        add_settings_field(
            'apiroot',
            'API Root',
            array($this, 'apiroot_callback'),
            'directid-setting-admin',
            'directid_setting_section_id'
        );

        add_settings_field(
            'oauthresource',
            'OAuth Resource',
            array($this, 'oauthresource_callback'),
            'directid-setting-admin',
            'directid_setting_section_id'
        );

        add_settings_field(
            'cdnpath',
            'CDN Path',
            array($this, 'cdnpath_callback'),
            'directid-setting-admin',
            'directid_setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $current_settings = get_option('didWidgetSettings'); //Used to get existing encrypted secret key so that it isn't lost in serialization when the form is submitted without a secret key defined
        $new_settings = array(); //Will be the resulting serialized settings object

        if (isset($input['clientid']))
            $new_settings['clientid'] = sanitize_text_field($input['clientid']);

        if (isset($input['secretkey']) && (strlen($input['secretkey']) > 0)) {
            $new_settings['secretkey'] = directid_encrypt_string(sanitize_text_field($input['secretkey']));
        } elseif (isset($current_settings['secretkey'])) { //Attempt to fall back to an existing key if the user has provided no input
            $new_settings['secretkey'] = $current_settings['secretkey'];
        }

        if (isset($input['company']))
            $new_settings['company'] = sanitize_text_field($input['company']);

        if (isset($input['apiroot']))
            $new_settings['apiroot'] = sanitize_text_field($input['apiroot']);

        if (isset($input['oauthresource']))
            $new_settings['oauthresource'] = sanitize_text_field($input['oauthresource']);

        if (isset($input['cdnpath']))
            $new_settings['cdnpath'] = sanitize_text_field($input['cdnpath']);

        return $new_settings;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * ClientID input
     */
    public function clientid_callback()
    {
        printf(
            '<input type="text" id="clientid" name="didWidgetSettings[clientid]" value="%s" size="60" />',
            isset($this->didWidgetSettings['clientid']) ? esc_attr($this->didWidgetSettings['clientid']) : ''
        );
    }

    /**
     * Secret key input
     * We do not set the value of the input as the secret key is stored encrypted and should not be visible to admins
     */
    public function secretkey_callback()
    {
        printf(
            '<input type="text" id="secretkey" name="didWidgetSettings[secretkey]" size="60" />',
            isset($this->didWidgetSettings['secretkey']) ? esc_attr($this->didWidgetSettings['secretkey']) : ''
        );
    }

    /**
     * Company Name input
     */
    public function companyName_callback()
    {
        printf(
            '<input type="text" id="company" name="didWidgetSettings[company]" value="%s" size="60" />',
            isset($this->didWidgetSettings['company']) ? esc_attr($this->didWidgetSettings['company']) : ''
        );
    }

    /**
     * Api Root input
     */
    public function apiroot_callback()
    {
        printf(
            '<input type="text" id="apiroot" name="didWidgetSettings[apiroot]" value="%s" size="60" />',
            isset($this->didWidgetSettings['apiroot']) ? esc_attr($this->didWidgetSettings['apiroot']) : ''
        );
    }

    /**
     * Oauth resource input
     */
    public function oauthresource_callback()
    {
        printf(
            '<input type="text" id="oauthresource" name="didWidgetSettings[oauthresource]" value="%s" size="60" />',
            isset($this->didWidgetSettings['oauthresource']) ? esc_attr($this->didWidgetSettings['oauthresource']) : ''
        );
    }

    /**
     * CDN path input
     */
    public function cdnpath_callback()
    {
        printf(
            '<input type="text" id="cdnpath" name="didWidgetSettings[cdnpath]" value="%s" size="60" />',
            isset($this->didWidgetSettings['cdnpath']) ? esc_attr($this->didWidgetSettings['cdnpath']) : ''
        );
    }
}

if (is_admin())
    $directid_settings_page = new DirectIdSettings();


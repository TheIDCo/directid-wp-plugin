<?php

function directid_get_oauth_token()
{

    $oAuthToken = '';

    $oAuthPath = 'https://login.windows.net/directiddirectory.onmicrosoft.com/oauth2/token';

    $response = wp_remote_post($oAuthPath, directid_get_oauth_args());

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        directid_throw_wp_error('did_get_oauth_token_response', 'oAuth token could not be retrieved - ' . $error_message );
    }
    else if ($response["response"]["code"] >= 400) {
        $error_message = $response['body'];
        directid_throw_wp_error('did_get_oauth_token_response', 'oAuth token could not be retrieved - ' . $error_message );
    }
    else {
        $jsonResponse = json_decode($response['body'], true);
        $oAuthToken = $jsonResponse['access_token'];
    }

    return $oAuthToken;
}

function directid_get_oauth_args()
{

    $headers = directid_get_oauth_headers();
    $body = directid_get_oauth_body();

    $args = array(
        'headers' => $headers,
        'body' => $body
    );

    return $args;
}

function directid_get_oauth_headers()
{
    $headers = array(
        'Host' => 'login.windows.net',
        'user-agent' => 'WordPress/' . get_bloginfo('url')
    );

    return $headers;
}

function directid_get_oauth_body(){

    $settings = get_option('didWidgetSettings');
    $clientID = $settings['clientid'];
    $clientSecret = directid_decrypt_string($settings['secretkey']);
    $oAuthResource = $settings['oauthresource'];

    $body = array(
        'grant_type' => 'client_credentials',
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'resource' => $oAuthResource
    );
    return $body;
}



?>


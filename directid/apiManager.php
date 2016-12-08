<?php

function directid_get_user_session_token($userRef, $email)
{
    return directid_get_user_session_token_from_api(directid_get_oauth_token(), $userRef, $email);
}

function directid_get_user_session_token_from_api($oAuthToken, $userRef, $email){

    $settings = get_option('didWidgetSettings');
    $userSessionEndpoint = $settings['apiroot'] . $settings['company'] . '/user/session/' . $userRef;

    if($email != null){
        $userSessionEndpoint = $userSessionEndpoint . '/email/' . $email;
    }

    $userSessionToken = '';

    $response = wp_remote_get($userSessionEndpoint, directid_get_api_request_args($oAuthToken));

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        directid_throw_wp_error('did_get_user_session_token_from_api_response', 'User Session token could not be retrieved - ' . $error_message );
    } else {
        $jsonResponse = json_decode($response['body'], true);
        $userSessionToken = $jsonResponse['token'];
    }

    return $userSessionToken;
}

function directid_get_api_request_args($oAuthToken)
{

    $args = array(
        'headers' => directid_get_api_request_headers($oAuthToken)
    );

    return $args;
}

function directid_get_api_request_headers($oAuthToken)
{
    $headers = array(
        'Authorization' => 'Bearer ' . $oAuthToken,
        'Content-Type' => 'application/json'
    );

    return $headers;
}

?>

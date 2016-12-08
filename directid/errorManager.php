<?php

function directid_throw_wp_error($code,$message){

    $errors = new WP_Error;

    $errors->add($code, $message);

    if ( is_wp_error( $errors ) ) {
        foreach ( $errors->get_error_messages() as $error ) {
            echo '<div>';
            echo '<strong>ERROR</strong>: ';
            echo 'DirectID - ' . $error . '<br/>';
            echo '</div>';
        }
    }

    return;
}

?>
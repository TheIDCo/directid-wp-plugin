<?php


function directid_get_encryption_settings()
{
    $settings = array();
    $settings["type"] = MCRYPT_RIJNDAEL_256;
    $settings["mode"] = MCRYPT_MODE_CBC;
    return $settings;
}

function directid_encrypt_string($input_value)
{

    $encryption_settings = directid_get_encryption_settings();



    if (defined('DID_KEY')) {

        # Create binary key from hex value set as constant in wp-config
        $key_hex = DID_KEY;
        $key = pack('H*', $key_hex);

        #Generate random seed
        $iv_size = mcrypt_get_iv_size($encryption_settings["type"], $encryption_settings["mode"]);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        #Encrypt string to binary
        $ciphertext = mcrypt_encrypt($encryption_settings["type"], $key, $input_value, $encryption_settings["mode"], $iv);

        #Package with encrypted binary with seed
        $ciphertext = $iv . $ciphertext;

        #Convert encrypted string to Base64 for storage
        $ciphertext_base64 = base64_encode($ciphertext);

        return $ciphertext_base64;

    }

    directid_throw_wp_error( 'did_encrypt_string_did_key', 'Encryption key not set.' );

    return 'DID_KEY - Not Set';
}

function directid_decrypt_string($secret_cipher){

    $encryption_settings = directid_get_encryption_settings();

    if (defined('DID_KEY')) {

        # Create binary key from hex value set as a constant in wp-config
        $key_hex = DID_KEY;
        $key = pack('H*', $key_hex);

        $iv_size = mcrypt_get_iv_size($encryption_settings["type"], $encryption_settings["mode"]); //Must match $iv_size in admin.php

        #Decode base 64
        $secret_cipher = base64_decode($secret_cipher);

        # retrieves the IV (random seed), iv_size should be created using mcrypt_get_iv_size() in decrypt method
        $iv = substr($secret_cipher, 0, $iv_size);

        # retrieves the cipher text (everything except the $iv_size in the front)
        $secret_cipher = substr($secret_cipher, $iv_size);

        # may remove 00h valued characters from end of plain text
        $secret_plain_text = mcrypt_decrypt($encryption_settings["type"], $key, $secret_cipher, $encryption_settings["mode"], $iv);

        # mycrypt adds nulls at the end of the string, strip them
        $secret_plain_text = rtrim($secret_plain_text, "\0");

        return $secret_plain_text;
    }

    directid_throw_wp_error( 'did_decrypt_string_did_key', 'Encryption key not set.' );

    return '';
}


?>
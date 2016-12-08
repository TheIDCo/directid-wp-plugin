<?php

function add_query_vars($aVars) {
    $aVars[] = "didToken";
    return $aVars;
}

add_filter('query_vars', 'add_query_vars');

function getParamFromPostRequest($param){

    if(isset($_REQUEST[$param]) && is_string($_REQUEST[$param]) && strlen($_REQUEST[$param]) > 0){
        return $_REQUEST[$param];
    }

    return null;
}

?>
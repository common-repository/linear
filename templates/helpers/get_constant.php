<?php

namespace Linear\Templates;

function get_constant( $listing, $key ){

    if( !$listing || !$key ){
        return false;
    }

    $constants = require LINEAR_PLUGIN_PATH . "/includes/constants_and_labels.php";

    if ( array_key_exists( $key, $constants ) ) {
        return $constants[$key];
    }

    $split_constant = explode( '@', $key, 2 );

    if ( array_key_exists( $split_constant[0], $constants ) ) {
        return $split_constant[0];
    }

    return $listing[$key];
    
}

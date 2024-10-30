<?php

namespace Linear\Templates;

function get_value( $listing = null, $key = null, $fallback = null){
    if( isset( $listing[$key] ) ){
        return $listing[$key];
    }

    if( $fallback !== null ){
        return $fallback;
    }

    return false;
}

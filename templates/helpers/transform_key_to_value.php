<?php

namespace Linear\Templates;

function transform_key_to_value( $listing, $key ){
    if( !$key || !$listing ){
        return "";
    }

    if( !isset($listing[$key]) ){
        return "";
    }

    return $listing[$key];
}

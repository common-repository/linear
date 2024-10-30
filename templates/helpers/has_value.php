<?php

namespace Linear\Templates;

// Wrapper for isset & !null
function has_value( $listing, $value ){
    if( !$listing || !$value ){
        return false;
    }

    if( !isset( $listing[$value] ) ){
        return false;
    }

    if( $listing[$value] === null || $listing[$value] === '' ){
        return false;
    }

    return true;
}

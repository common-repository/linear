<?php

namespace Linear\Templates;

// Wrapper for isset & !null
function has_array_values( $listing, $values ){
    if( !$listing || !$values ){
        return false;
    }

    $return = false;

    foreach( $values as $value ){
        if( !isset( $listing[ $value ] ) ){
            continue;
        }

        if( $listing[ $value ] === null || $listing[ $value ] === '' ){
            continue;
        }

        $return = true;
    }

    return $return;
}

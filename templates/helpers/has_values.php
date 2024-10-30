<?php

namespace Linear\Templates;

function has_values( $listing, $values ){
    if( !$listing || !$values ){
        return false;
    }

    $checks = [];
    foreach( $values as $key => $value ){
        array_push( $checks, has_value( $listing, $value ) );
    }

    if( in_array( false, $checks) ){
        return false;
    }

    return true;
}

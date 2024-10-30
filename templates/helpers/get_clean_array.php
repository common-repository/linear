<?php

namespace Linear\Templates;

function get_clean_array( $listing, $values, $default = [] ){
    if( !$listing || !$values ){
        return $default;
    }

    $new_array = [];

    foreach( $values as $value ){
        if( !isset( $listing[ $value ] ) || !$listing[ $value ] ){
            continue;
        }

        array_push( $new_array, $listing[ $value ] );
    }

    return $new_array;
}

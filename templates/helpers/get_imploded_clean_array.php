<?php

namespace Linear\Templates;

function get_imploded_clean_array( $listing, $values, $delimiter = ' ', $default = [] ){
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

    if( $new_array === [] ){
        return $new_array;
    }

    return esc_html( implode( $delimiter, $new_array ) );
}

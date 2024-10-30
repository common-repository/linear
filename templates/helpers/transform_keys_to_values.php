<?php

namespace Linear\Templates;

function transform_keys_to_values( $listing, $keys ){
    if( !$keys ){
        return "";
    }

    $values = [];

    foreach( $keys as $key){
        $transformed_value = transform_key_to_value( $listing, $key );
        if( $transformed_value ){
            array_push( $values, $transformed_value );
        }
    }

    return $values;
}

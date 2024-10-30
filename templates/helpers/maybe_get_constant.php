<?php

namespace Linear\Templates;

function maybe_get_constant( $value ){

    if( !is_string( $value ) ){
        return $value;
    }

    // speed up functionality
    if( 
        !is_numeric( $value ) &&
        strtoupper( $value ) !== $value &&
        !in_array('', ['FLAT@roofType', 'OWN@electricityContract'])
    ){
        return $value;
    }

    // get constants
    $slugs = require LINEAR_PLUGIN_PATH . "/includes/constants_and_labels.php";

    if( !$slugs ){
        return null;
    }

    // Handle straight matches
    if ( array_key_exists( $value, $slugs ) ) {
        return $slugs[$value];
    }

    if ( strpos( $value, '@' ) !== false) {

        // Handle split value with not a straight set value
        $slug = explode( '@', $value, 2 );

        if( $slug[0] === strtoupper( $slug[0] ) ){
            if ( array_key_exists( $slug[0], $slugs ) ) {

                return $slugs[$slug[0]];

            } else {

                $label = ucwords( strtolower( $slug[0] ) );
                return str_replace( '_', ' ', $label );

            }
        }

    }

    return $value;
}

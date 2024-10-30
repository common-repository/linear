<?php

namespace Linear\Templates;

function get_linear_option_value( $option_name ){
    if( !$option_name ){
        return null;
    }

    if( !is_string( $option_name ) ){
        return null;
    }

    $linear_settings = get_option( 'linear_settings' );

    if( !$linear_settings || !is_array( $linear_settings ) ){
        return null;
    }

    if( !array_key_exists( $option_name, $linear_settings ) ){
        return null;
    }

    return $linear_settings[$option_name];
}

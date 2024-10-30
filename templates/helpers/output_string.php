<?php

namespace Linear\Templates;

/**
 * Supports mixed input types, outputs string
 */
function output_string( $listing, $input ){
    if( !$input ){
        return "";
    }

    if( is_string( $input ) ){
        return esc_html( transform_key_to_value( $listing, $input ) );
    }

    if( is_array( $input ) ){
        return esc_html( implode( ', ', transform_keys_to_values( $listing, $input ) ) );
    }

    if( is_object( $input ) ){
        $delimiter = ", ";
        if( $input->delimiter ){
            $delimiter = $input->delimiter;
        }

        if( isset( $input->values ) ){
            return esc_html( implode( $delimiter, transform_keys_to_values( $listing, $input->values ) ) );
        }
    }

    return "";
}

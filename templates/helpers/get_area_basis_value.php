<?php

namespace Linear\Templates;

function get_area_basis_value( $listing ){
    if( !has_value( $listing, 'areaBasis' ) ){
        return "";
    }

    $label = '';

    if( !has_value( $listing, 'areaBasisControlMeasured' ) || true ){
        $label = get_imploded_clean_array( $listing, [ 'areaBasisMeasuringCompany', 'areaBasisMeasuringPerson' ], ', ', '' );

        if ( !empty( $label ) ) {
            $label = _x( 'by', 'Used in "measured by" context', 'linear' ) . ' ' . $label;
        }

        if ( has_value( $listing, 'areaBasisMeasuringDate' ) ) {
            $label = $label . ( empty( $label ) ? '' : ' ' ) . _x( 'on', 'Used in "on date" context before date', 'linear' ) . ' ' . date_i18n( get_option( 'date_format' ), strtotime( get_object_value( $listing, 'areaBasisMeasuringDate' ) ) );
        }

        if ( !empty( $label ) ) {
            $label = __( 'Verification and measurement', 'linear' ) . ' ' . $label . '.';
        }
    } else {
        $label = __( 'The area is not checked and the actual area may be larger or smaller than stated.', 'linear' );
    }

    if( $label ){
        return get_object_value( $listing, 'areaBasis' ) . '<small>(' . esc_html( $label ) . ')</small>';
    }

    return get_object_value( $listing, 'areaBasis' );
}

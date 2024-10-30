<?php

namespace Linear\Templates;

function get_maintenance_charge_value( $listing, $default = "0 €" ){
    if( !$listing ){
        return $default;
    }

    $maintenance_charge = get_object_value( $listing, 'formatted_maintenanceCharge', "0 €" );
    $additional_charges = [];

    if( has_value( $listing, 'formatted_mandatoryCharges') ){
        $additional_charges[] =  str_replace( ' ', '&nbsp;', __( 'Mandatory charges', 'linear' ) ) . ' ' . get_object_value( $listing, 'formatted_mandatoryCharges' );
    }

    if( has_value( $listing, 'formatted_fundingCharge') && substr(get_object_value( $listing, 'formatted_fundingCharge' ), 0, 6) !== "0 €" ){
        $additional_charges[] =  str_replace( ' ', '&nbsp;', __( 'Funding charge', 'linear' ) ) . ' ' . get_object_value( $listing, 'formatted_fundingCharge' );
    }

    if( has_value( $listing, 'formatted_renovationCharge') && substr(get_object_value( $listing, 'formatted_renovationCharge' ), 0, 6) !== "0 €" ){
        $additional_charges[] =  str_replace( ' ', '&nbsp;', __( 'Renovation charge', 'linear' ) ) . ' ' . get_object_value( $listing, 'formatted_renovationCharge' );
    }

    if( has_value( $listing, 'formatted_plotRentCharge') && substr(get_object_value( $listing, 'formatted_plotRentCharge' ), 0, 6) !== "0 €" ){
        $additional_charges[] =  str_replace( ' ', '&nbsp;', __( 'Plot rent charge', 'linear' ) ) . ' ' . get_object_value( $listing, 'formatted_plotRentCharge' );
    }

    if( has_value( $listing, 'formatted_otherCharge') && substr(get_object_value( $listing, 'formatted_otherCharge' ), 0, 6) !== "0 €" ){
        $additional_charges[] =  str_replace( ' ', '&nbsp;', __( 'Other charge', 'linear' ) ) . ' ' . get_object_value( $listing, 'formatted_otherCharge' );
    }

    // Main content
    $merged_charges = '';
    $merged_charges .= ( $maintenance_charge ? esc_html( $maintenance_charge ) : '' );
    $merged_charges .= ( $maintenance_charge && !empty( $additional_charges ) ? '<br />' : '' );
    $merged_charges .= ( !empty( $additional_charges ) ? '<small>' . esc_html( '(' . implode( ' + ', $additional_charges ) . ')' ) . '</small>' : '' );

    if( !$merged_charges ){
        return $default;
    }

    return $merged_charges;
}

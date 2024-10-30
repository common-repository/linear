<?php

namespace Linear\Templates;

function get_company_logo( $realtor ){

    $defined_company_image = get_linear_option_value( 'company_logo' );
    if( $defined_company_image ){
        if ( wp_attachment_is_image( get_linear_option_value( 'company_logo' ) ) ) {
            return wp_get_attachment_url( get_linear_option_value( 'company_logo' ) );
        }
    }

    // fallback
    if( $realtor['companyLogo'] ){
        return $realtor['companyLogo'];
    }

    return null;
}

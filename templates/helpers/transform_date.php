<?php

namespace Linear\Templates;

function transform_date( $date ){
    if( !$date ){
        return null;
    }

    if( !is_string( $date ) ){
        return $date;
    }

    return date_i18n( get_option( 'date_format' ), strtotime( $date ) );
}

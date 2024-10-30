<?php

namespace Linear\Templates;

function get_presentation( $listing, $types = [], $default = null ){

    $next_presentation = null;
    
    if ( has_value( $listing, 'presentations' ) ) {
        foreach ( get_object_value( $listing,  'presentations' ) as $presentation ) {
            if ( in_array( $presentation['type'], $types ) ) {
                $date = new \DateTime( $presentation['date'] );
                $end_time = new \DateTime( $presentation['endTime'] );
                $combined_date = new \DateTime($date->format('Y-m-d') . ' ' .$end_time->format('H:i:s'));

                if ( strtotime('today') <= $combined_date->getTimestamp() ) {
                    if ( empty( $next_presentation ) || strtotime( $presentation['date'] ) <= strtotime( $next_presentation['date'] ) ) {
                        $next_presentation = $presentation;
                    }
                }
            }
        }
    }

    if ( empty( $next_presentation ) ) {
        return esc_html( $default );
    }

    return esc_html( sprintf(
        '%s %s&nbsp;-&nbsp;%s',
        str_replace ( ' ', '&nbsp;', date_i18n( get_option( 'date_format' ), strtotime( $next_presentation['date'] ) ) ),
        str_replace ( ' ', '&nbsp;', date_i18n( get_option( 'time_format' ), strtotime( $next_presentation['startTime'] ) ) ),
        str_replace ( ' ', '&nbsp;', date_i18n( get_option( 'time_format' ), strtotime( $next_presentation['endTime'] ) ) )
    ) );
}

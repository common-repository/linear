<?php

namespace Linear\Templates;

function map_value_labels( $listing, $data ){
    $output = [];
    
    if( !$listing || !$data ){
        return $output;
    }

    foreach( $data as $key => $single ){
        if( is_array( $single ) ){
            if( isset( $single['value'] ) && !in_array( $single['value'], [null, ''] ) && isset( $single['label'] )  ){
                array_push( $output, [
                    'value' => maybe_get_constant( $single['value'] ),
                    'label' => $single['label'],
                ] );
            }
        } else {
            if( isset( $listing[$key] ) && $listing[$key] ){
                array_push( $output, [
                    'value' => maybe_get_constant( $listing[$key] ),
                    'label' => $single,
                ] );
            }
        }
    }

    return $output;
}

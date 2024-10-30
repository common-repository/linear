<?php

namespace Linear\Templates;

function text( $listing, $data ) {
    if( !isset( $data->value ) || !$data->value ){
        return "";
    }

    return '<p>' . output_string( $listing, $data->values ) . '</p>';
}

?>
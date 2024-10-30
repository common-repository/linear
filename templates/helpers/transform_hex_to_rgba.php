<?php

namespace Linear\Templates;

/**
 * Based on https://mekshq.com/how-to-convert-hexadecimal-color-code-to-rgb-or-rgba-using-php/
 */

function transform_hex_to_rgba( $color, $opacity = false, $default = 'rgb(0,0,0)' ) {

    if( empty($color) ){
        return $default; 
    }

    if ( $color[0] == '#' ) {
        $color = substr( $color, 1 );
    }

    if (strlen($color) == 6) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }

    $rgb =  array_map( 'hexdec', $hex );

    if($opacity){
        if( abs( floatval( $opacity ) ) >= 1 ){
            $opacity = 1.0;
        }
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    } else {
        $output = 'rgb('.implode(",",$rgb).')';
    }

    return $output;
}

?>
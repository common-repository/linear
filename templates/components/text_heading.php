<?php

namespace Linear\Templates;

function text_heading( $string = '' ) {
    if( !$string ){
        return "";
    }

    return '<h2 class="linear-c-card__heading linear-u-mb-0.5 component-text-heading">' . $string . '</h2>';
}

?>
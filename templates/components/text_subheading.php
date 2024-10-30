<?php

namespace Linear\Templates;

function text_subheading( $string = '' ) {
    if( !$string ){
        return "";
    }

    return '<p class="linear-u-h6 linear-u-mb-0.5 component-text-subheading">' . $string . '</p>';
}

?>